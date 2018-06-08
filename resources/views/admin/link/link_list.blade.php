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
    @include("admin.static")
    <link rel="stylesheet" href="{{asset('css/admin/main.css')}}" media="all"/>
    <style type="text/css">
        .layui-table td, .layui-table th {
            text-align: center;
        }
        .layui-table td {
            padding: 5px;
        }
        .layui-input-inline{margin:10px 5px; }
        .layui-inline a{height:36px;margin:10px 5px; }
        .layui-btn i{margin-right:10px;}
    </style>
</head>
<body class="main_body">
<div class="layui-layout layui-layout-admin">
@include("admin/header")
<!-- 左侧导航 -->
@include("admin/left")
<!-- 右侧内容 -->
    <div class="layui-body layui-form">
        <div class="layui-tab marg0" lay-filter="bodyTab" id="top_tabs_box">
            <ul class="layui-tab-title top_tab" id="top_tabs">
                <li class="layui-this"><cite>友情链接管理</cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                    <form id="myform" action="lists" method="get">
                    <blockquote class="layui-elem-quote news_search">
                        <input type="text" id="curr_p" name="p" value="1" hidden />
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input type="text" name="id" value="{{$id or ''}}" placeholder="请输入id" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="name" value="{{$name or ''}}" placeholder="请输入名称" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="href" value="{{$href or ''}}" placeholder="请输入链接" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="email" value="{{$email or ''}}" placeholder="请输入站长邮箱" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <select name="type" class="newsLook"  lay-filter="browseLook">
                                    <option value="" >全部</option>
                                    @foreach(\App\Model\Link::$TYPE  as $k=>$v)
                                        <option value="{{$k}}" <?php if(!empty($type)&&$k==$type){echo "selected";} ?> >{{$k}}--{{$v['name']}}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="setime" value="{{$setime or ''}}" class="layui-input" id="test10" placeholder="开始结束时间">
                            </div>
                            <a class="layui-btn search_btn" onclick="mysearch();"><i class="iconfont icon-sousuo"></i>查询</a>
                            @if(\App\Model\Admin::isAuth("/link/export"))
                            <a href="javascript:exportTable();" class="layui-btn search_btn" ><i class="iconfont icon-xiazai"></i>导出</a>
                            @endif
                            @if(\App\Model\Admin::isAuth("/link/add"))
                            <a href="add" class="layui-btn search_btn"><i class="iconfont icon-tianjia"></i>添加</a>
                            @endif
                        </div>
                    </blockquote>
                    </form>
                    <div class="layui-form links_list">
                        <table class="layui-table">
                            <thead>
                            <tr>
                            <th>id</th>
                            <th>名称</th>
                            <th>链接</th>
                            <th>站长邮箱</th>
                            <th>类型</th>
                            <th></th>
                            <th></th>
                            <th>操作</th>
                            </tr>
                            </thead>
                            <tbody class="links_content">
                            @foreach($list as $itme)
                            <tr>
                                <td>{{$itme->id}}</td>
                                <td>{{$itme->name}}</td>
                                <td>{{$itme->href}}</td>
                                <td>{{$itme->email}}</td>
                                <td><?php echo \App\Model\Link::$TYPE[$itme->type]["name"] ?></td>
                                <td>{{$itme->created_at}}</td>
                                <td>{{$itme->updated_at}}</td>
                                <td>
                                    @if(\App\Model\Admin::isAuth("/link/show"))
                                        <a href="show?id={{$itme->id}}"><i class="iconfont icon-dayinmoban"></i> 查看</a>
                                    @endif
                                    @if(\App\Model\Admin::isAuth("/link/edit"))
                                    <a href="edit?id={{$itme->id}}" ><i class="iconfont icon-xuqiudengji"></i> 编辑</a>
                                    @endif
                                    @if(\App\Model\Admin::isAuth("/link/delete"))
                                    <a href="javascript:deleteById({{$itme->id}});" ><i class="iconfont icon-yichu"></i> 删除</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="page"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部 -->
    @include("admin/footer")
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
            ,count:'{{$list->total()}}',
            'limit':10,
             curr:'{{$list->currentPage()}}',
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
