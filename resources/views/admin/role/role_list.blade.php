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
                <li class="layui-this"><cite>管理</cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                    <form id="myform" action="getList" method="get">
                    <blockquote class="layui-elem-quote news_search">
                        <input type="text" id="curr_p" name="p" value="1" hidden />
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                 <input type="text" name="id" value="{{$id or ''}}" placeholder="请输入id" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                 <input type="text" name="name" value="{{$name or ''}}" placeholder="请输入角色名称" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                 <input type="text" name="created_at" value="{{$created_at or ''}}" placeholder="请输入" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                 <input type="text" name="updated_at" value="{{$updated_at or ''}}" placeholder="请输入" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="setime" value="{{$setime or ''}}" class="layui-input" id="test10" placeholder="开始结束时间">
                            </div>
                            <a class="layui-btn search_btn" onclick="mysearch();">查询</a>
                            <a href="javascript:exportTable();" class="layui-btn search_btn" >导出</a>
                            <a href="index" class="layui-btn search_btn" >添加</a>
                        </div>
                    </blockquote>
                    </form>
                    <div class="layui-form links_list">
                        <table class="layui-table">
                            <colgroup>
                                 <col>
                                 <col>
                                 <col>
                                 <col>
                            </colgroup>
                            <thead>
                            <tr>
                                 <th>id</th>
                                 <th>角色名称</th>
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
                                <td>{{$itme->created_at}}</td>
                                <td>{{$itme->updated_at}}</td>
                                <td><a href="index?id={{$itme->id}}" ><i class="iconfont icon-edit"></i> 编辑</a>
                                    <a href="javascript:deleteById({{$itme->id}});" ><i class="layui-icon"></i> 删除</a>
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
    layui.use(['laypage','layer','laydate'], function(){
        var laydate = layui.laydate;
        var laypage = layui.laypage;
        var layer = layui.layer;
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
                    location.href ="getList";
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
