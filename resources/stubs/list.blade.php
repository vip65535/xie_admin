<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.ico">
    @@include("admin.static")
    <link rel="stylesheet" href="@{{asset('css/admin/main.css')}}" media="all"/>
</head>
<body class="main_body">
<div class="layui-layout layui-layout-admin">
@@include("admin/header")
<!-- 左侧导航 -->
@@include("admin/left")
<!-- 右侧内容 -->
    <div class="layui-body layui-form">
        <div class="layui-tab marg0" lay-filter="bodyTab" id="top_tabs_box">
            <ul class="layui-tab-title top_tab" id="top_tabs">
                <li class="layui-this"><cite><?php echo$table_comment?>管理</cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                    <form id="myform" action="lists" method="get">
                    <blockquote class="layui-elem-quote news_search">
                        <input type="text" id="curr_p" name="p" value="{{$p or 1}}" hidden />
                        <div class="layui-inline">
@foreach($colums as $colum)
@if(count(explode(":",$colum['column_comment']))>1)
                            <div class="layui-input-inline">
                                <select name="<?php echo$colum['column'];?>" class="newsLook"  lay-filter="browseLook">
                                    <option value="" >全部</option>
                                    @@foreach(\App\Model\<?php echo$TableName."::$".strtoupper($colum['column']);?>  as $k=>$v)
                                        <option value="@{{$k}}" <?php echo '<?php if(!empty($'.$colum['column'].')&&$k==$'.$colum['column'].'){echo "selected";} ?>';?> >@{{$k}}--@{{$v['name']}}</option>
                                        @@endforeach
                                </select>
                            </div>
@else
@if($colum['column']=="created_at"||$colum['column']=="updated_at")
@else
                            <div class="layui-input-inline">
                                <input type="text" name="<?php echo$colum['column'];?>" value="{{$<?php echo$colum['column'];?> or ''}}" placeholder="请输入<?php echo$colum['simple_column_comment'];?>" class="layui-input search_input">
                            </div>
@endif
@endif
@endforeach
                            <div class="layui-input-inline">
                                <input type="text" name="setime" value="@{{$setime or ''}}" class="layui-input" id="test10" placeholder="开始结束时间">
                            </div>
                            <a class="layui-btn search_btn" onclick="mysearch();"><i class="iconfont icon-sousuo"></i>查询</a>
                            @@if(\App\Model\Admin::isAuth("/<?php echo $tableName?>/export"))
                            <a href="javascript:exportTable();" class="layui-btn search_btn" ><i class="iconfont icon-xiazai"></i>导出</a>
                            @@endif
                            @@if(\App\Model\Admin::isAuth("/<?php echo $tableName?>/add"))
                            <a href="add" class="layui-btn search_btn"><i class="iconfont icon-tianjia"></i>添加</a>
                            @@endif
                        </div>
                    </blockquote>
                    </form>
                    <div class="layui-form links_list">
                        <table class="layui-table">
                            <thead>
                            <tr>
@foreach($colums as $colum)
                            <th><?php  echo $colum['simple_column_comment'];?></th>
@endforeach
                            <th>操作</th>
                            </tr>
                            </thead>
                            <tbody class="links_content">
                            @@foreach($list as $itme)
                            <tr>
@foreach($colums as $colum)
@if(count(explode(":",$colum['column_comment']))>1)
                                <td><?php echo '<?php echo \App\Model\\'.$TableName.'::$'.strtoupper($colum['column']).'[$itme->'.$colum['column'].']["name"] ?>'?></td>
@else
                                <td>{{$itme-><?php echo$colum['column'];?>}}</td>
@endif
@endforeach
                                <td nowrap="true">
                                    @@if(\App\Model\Admin::isAuth("/<?php echo $tableName?>/show"))
                                        <a href="show?id=@{{$itme->id}}"><i class="iconfont icon-dayinmoban"></i> 查看</a>
                                    @@endif
                                    @@if(\App\Model\Admin::isAuth("/<?php echo $tableName?>/edit"))
                                    <a href="edit?id=@{{$itme->id}}" ><i class="iconfont icon-xuqiudengji"></i> 编辑</a>
                                    @@endif
                                    @@if(\App\Model\Admin::isAuth("/<?php echo $tableName?>/delete"))
                                    <a href="javascript:deleteById(@{{$itme->id}});" ><i class="iconfont icon-yichu"></i> 删除</a>
                                    @@endif
                                </td>
                            </tr>
                            @@endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="page"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部 -->
    @@include("admin/footer")
</div>
<script>
    function mysearch(){
        $("#myform").submit();
    }
    //完整功能
    layui.use(['laypage','layer','laydate','form'], function(){
        var laydate = layui.laydate;
        var laypage = layui.laypage;
        var layer = layui.layer;
        var form = layui.form;
        form.render();
        //日期时间范围
        laydate.render({
            elem: '#test10'
            ,type: 'datetime'
            ,min: '1999-8-11 12:30:00'
            ,max: '2117-8-18 12:30:00'
            ,range: true
        });
        laypage.render({
            elem: 'page'
            ,count:'@{{$list->total()}}',
            'limit':10,
             curr:'@{{$list->currentPage()}}',
            layout: ['count', 'prev', 'page', 'next', 'skip']
            ,jump: function(obj,first){
                if(!first){
                    layer.load(2, {shade: false});
                    $("#curr_p").val(obj.curr);
                     mysearch();
                }
            }
        });
    });
    function deleteById(id){
        layer.confirm('确定要删除吗？',{'title':"提示",'icon':0,'closeBtn':0,'shadeClose':true}, function(index){
            $.post("delete",{
                "id":id,
            },function(data){
                if(data.code==1){
                    location.href ="lists";
                }
            },"json")
            layer.close(index);
        });
    }
    function exportTable()
    {
        var param = $("#myform").serialize()
        window.open("export?"+param);
        return;
    }
</script>
</body>
</html>
