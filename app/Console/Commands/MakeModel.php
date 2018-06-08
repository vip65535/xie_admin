<?php

namespace App\Console\Commands;

use App\Model\Functions;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MakeModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xiely:make {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成模板';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem, Composer $composer)
    {
        parent::__construct();
        $this->file    = $filesystem;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tableName = $this->argument("table");
        $this->getTableInfo(env("DB_DATABASE"),$tableName);
        $data = $this->table_info;
       //var_dump($data['colum_str']);
        $this->makeController($data);
        $this->makeModel($data);
        $this->makeHtml($data);
        $this->makeFunctions($data);

    }
    //生成实体模板
    public function makeFunctions($data){
        var_dump($data['table_comment']);
        var_dump($data['tableName']);
        Functions::deleteByHref("/".$data['tableName']."/lists");
        $pf = new Functions;
        $pf->pid=0;
        $pf->type=2;
        $pf->name=$data['table_comment'];
        $pf->href="/".$data['tableName']."/lists";
        $pf->save();

        $functions = new Functions;
        $functions->pid=$pf->id;
        $pf->type=1;
        $functions->name="新增权限";
        $functions->href="/".$data['tableName']."/add";
        $functions->save();

        $functions = new Functions;
        $functions->pid=$pf->id;
        $pf->type=1;
        $functions->name="删除权限";
        $functions->href="/".$data['tableName']."/delete";
        $functions->save();

        $functions = new Functions;
        $functions->pid=$pf->id;
        $pf->type=1;
        $functions->name="查询权限";
        $functions->href="/".$data['tableName']."/lists";
        $functions->save();

        $functions = new Functions;
        $functions->pid=$pf->id;
        $pf->type=1;
        $functions->name="编辑权限";
        $functions->href="/".$data['tableName']."/edit";
        $functions->save();

        $functions = new Functions;
        $functions->pid=$pf->id;
        $pf->type=1;
        $functions->name="查看权限";
        $functions->href="/".$data['tableName']."/show";
        $functions->save();

        $functions = new Functions;
        $functions->pid=$pf->id;
        $pf->type=1;
        $functions->name="导出权限";
        $functions->href="/".$data['tableName']."/export";
        $functions->save();


    }
    //生成实体模板
    public function makeModel($data){
        $this->make("model",base_path()."/app/Model/".$data['TableName'].".php",$data);
    }
    //生成controller模板
    public function makeController($data){
        $this->make("controller",base_path()."/app/Http/Controllers/Admin/".$data['TableName']."Controller.php",$data);
    }
    //生成html模板
    public function makeHtml($data){
        $this->make("index",base_path()."/resources/views/admin/".$data['table_name']."/".$data['table_name']."_index.blade.php",$data);
        $this->make("list",base_path()."/resources/views/admin/".$data['table_name']."/".$data['table_name']."_list.blade.php",$data);
    }
    //获取模板路径
    public function getTmpPath(){
        return base_path().DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."stubs".DIRECTORY_SEPARATOR;
    }
    //生成模板
    public function make($tmpName,$destPath,$data){
        $content =view()->file($this->getTmpPath().$tmpName.".blade.php",$data)->render();
        $this->file->put($this->replaceEnvPath($destPath),$content);
    }
    //转换成环境路径
    public function replaceEnvPath($path){
        $path =str_replace("/",DIRECTORY_SEPARATOR,$path);
        $this->makePath($path);
        return $path;
    }
    public function makePath($path){
        $path = substr($path,0,strrpos($path,DIRECTORY_SEPARATOR)) ;
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
    }
    //获取表信息
    public function getTableInfo($data_base,$table_name){
        $this->table = $this->getTable($data_base,$table_name);
        $this->columns = $this->getColumns($data_base,$table_name);
        $simple_columns = array();
        $show_const = array();
        if($this->columns){
            foreach ($this->columns as $k=>$v){
                $simple_columns[]= array(
                    "column"=>$v->COLUMN_NAME,
                    "type"=>$v->DATA_TYPE,
                    "index"=>$v->ORDINAL_POSITION,
                    "column_comment"=>$v->COLUMN_COMMENT,
                    "simple_column_comment"=>explode(":",$v->COLUMN_COMMENT)[0],
                    "is_null"=>$v->IS_NULLABLE=="NO"?true:false,
                );
                if($v->DATA_TYPE=='int'||$v->DATA_TYPE=='tinyint'){
                    $comment = $v->COLUMN_COMMENT;
                    if(strpos($comment,":")){
                        list($name,$status_str)= explode(":",$comment);
                        $status =explode(",",$status_str);
                        $status_array = array();
                        foreach ($status as $statu){
                            list($value,$const,$lable)= explode("-",$statu);
                            $status_array[]=array("name"=>$lable,"const"=>strtoupper($const),"value"=>$value);
                        }
                        $show_const[strtoupper($v->COLUMN_NAME)]=$status_array;
                    }
                }
            }
        }
        $this->table_info = array(
            "table_comment"=>$this->table->TABLE_COMMENT,
            "table_name"=>$this->table->TABLE_NAME,
            "colums"=>$simple_columns,
            "show_const"=>$show_const,
        );

        $this->table_info['tablename'] = strtolower($this->table->TABLE_NAME);
        $this->table_info['TABLENAME'] = strtoupper($this->table->TABLE_NAME);
        $this->table_info['TableName'] = $this->getClassName($this->table->TABLE_NAME);
        $this->table_info['tableName'] = $this->getFieldName($this->table->TABLE_NAME);
        $this->table_info['colum_str'] = implode(",",array_column($simple_columns,"column"));
        $this->table_info['colum_comment_str'] = implode(",",array_column($simple_columns,"simple_column_comment"));

    }
    public function getFieldName($tabe_name){
        $names =  explode("_",strtolower($tabe_name));
        $newName="";
        for ($i=0;$i<count($names);$i++){
            if($i==0){
                $newName.= $names[$i];
            }else{
                $newName.= strtoupper(substr($names[$i],0,1)).substr($names[$i],1);
            }
        }
        return $newName;
    }
    public function getClassName($tabe_name){
        $names =  explode("_",$tabe_name);
        $newName="";
        for ($i=0;$i<count($names);$i++){
            $newName.= strtoupper(substr($names[$i],0,1)).substr($names[$i],1);
        }
        return $newName;
    }
    public function getTable($data_base,$table_name){
        $table = DB::connection("schema")->select("select * from TABLES where TABLE_SCHEMA =? AND TABLE_NAME =?",array($data_base,$table_name));
        if(count($table)>0){
            return $table[0];
        }
        return null;
    }

    public function getColumns($data_base,$table_name){
        $Columns = DB::connection("schema")->select("select * from COLUMNS where TABLE_SCHEMA =? AND TABLE_NAME =?",array($data_base,$table_name));
        if(count($Columns)>0){
            return $Columns;
        }
        return null;
    }

}
