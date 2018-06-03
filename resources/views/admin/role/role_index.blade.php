<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理模板</title>
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
    <link rel="stylesheet" href="{{asset('css/admin/news.css')}}" media="all"/>
    <link rel="stylesheet" href="{{asset('js/ztree/metroStyle.css')}}" media="all"/>
    <script type="text/javascript" src="{{asset('js/ztree/jquery.ztree.all.js')}}"></script>
    <style type="text/css">
        .layui-table td, .layui-table th {
            text-align: center;
        }
        .layui-table td {
            padding: 5px;
        }
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
                <li class="layui-this"><cite>编辑</cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                    <form class="layui-form " action="add" method="post">
                        <input hidden name="id" value="{{$role->id or ''}}"/>
                        <div class="layui-form-item">
                            <label class="layui-form-label">角色名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" value="{{$role->name or ''}}"
                                       class="layui-input newsName" lay-verify="required" placeholder="角色名称">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">权限</label>
                            <div class="layui-input-block" style="height: 300px;width:200px;overflow: scroll;">
                                <ul id="tree" class="ztree"></ul>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <a onclick="submitForm();" class="layui-btn" lay-submit="" lay-filter="addNews">提交</a>
                                <button type="reset" onclick="javascript:history.go(-1);"
                                        class="layui-btn layui-btn-primary">返回
                                </button>
                            </div>
                        </div>
                    </form>
                    <!--主体结束-->
                </div>
            </div>
        </div>
    </div>
    <!-- 底部 -->
    @include("admin/footer")
</div>
<script>
    var zTreeObj;
    var setting = {
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };

    function flushTree(){
        $.post("../functions/getAllJson",{
            "role_id":"{{$role->id or ''}}"
        },function(data){
            if(data.code==1){
                role_ids =  data.data.functions_ids;
                tree =  data.data.tree;
                for(i=0;i<tree.length;i++){
                    if(role_ids){
                        for(j=0;j<role_ids.length;j++){
                            if(tree[i].id==role_ids[j]){
                                tree[i]['checked']=true;
                            }
                        }
                    }

                }

                zTreeObj = $.fn.zTree.init($("#tree"), setting, tree);


            }
        });
    }
    $(document).ready(function(){
        flushTree();
    });

    function submitForm(){
        var nodes = zTreeObj.getCheckedNodes(true);
        console.log(nodes[0].id);
        var data =[];
        for(var i=0;i<nodes.length;i++){
            data[i] = {'id':nodes[i].id};
        }
        $.post("add",{
            "id":$("input[name='id']").val(),
            "name":$("input[name='name']").val(),
            "data":data,
        },function(data){
            if(data.code==1){
                layer.msg(data.m);
            }else{
                layer.msg(data.m);
            }
        },"json")
    }

</script>
</body>
</html>
