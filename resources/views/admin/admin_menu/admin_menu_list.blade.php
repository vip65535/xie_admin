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
    <link rel="stylesheet" href="{{asset('js/ztree/metroStyle.css')}}" media="all"/>
    <script type="text/javascript" src="{{asset('js/ztree/jquery.ztree.all.js')}}"></script>
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
            <a class="layui-btn layui-btn-sm layui-btn-warm" style="margin-left: 100px;margin-top:5px;" onclick="addForm();">新增顶级菜单</a>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <div class="layui-row layui-col-space10">
                        <div class="layui-col-md2">
                            <ul id="tree" class="ztree"></ul>
                        </div>
                        <div class="layui-col-md10">

                            <form id="myform" class="layui-form" action="{{url("adminMenu/add")}}" method="post">
                                <input style="display: none;" type="text" name="id" value="" >

                                <div class="layui-form-item">
                                    <label class="layui-form-label">父节点</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="pid" value="" class="layui-input newsName" lay-verify="required" placeholder="父节点">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">排序</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="sort" value="" class="layui-input newsName" lay-verify="required" placeholder="排序">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">功能名称</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="name" value="" class="layui-input newsName" lay-verify="required" placeholder="功能名称">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">图标</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="icon_class" value="" class="layui-input newsName" lay-verify="required" placeholder="图标">
                                        <a onclick="selectIcon();"  class="layui-btn layui-btn-xs">迷你按钮</a>
                                    </div>

                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">链接</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="href" value="" class="layui-input newsName" lay-verify="required" placeholder="链接">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <a class="layui-btn" onclick="addForm();" lay-submit="" lay-filter="addNews">提交</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                </div>

            </div>
            </div>
        </div>
    </div>
    <!-- 底部 -->
    @include("admin/footer")
</div>
<script>
    var zTreeObj;
    var IDMark_A = "_a";
    var setting = {
        view: {
            addDiyDom: addDiyDom
        },
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            onClick: onClick
        }
    };
    function checkIcon(obj){
        $("input[name='icon_class']").val($(obj).attr("data"));
        layer.closeAll();
    }
    function onClick(event, treeId, treeNode, clickFlag) {
        console.log(treeNode);
        formDataLoad("myform",treeNode);
    }

    function addForm() {
        var data = $("#myform").serialize();
        $.post("/admin/adminMenu/add",data,function(data){
            if(data.code==1){
                layer.msg(data.m);
                flushTree();
            }
        });
    }

    function flushTree(){
        $.post("/admin/adminMenu/getAllJson",{
        },function(data){
            if(data.code==1){
                zTreeObj = $.fn.zTree.init($("#tree"), setting, data.data.tree);
            }
        });
    }
    function addDiyDom(treeId, treeNode) {
        var aObj = $("#" + treeNode.tId + IDMark_A);
        var editStr='';
         if(treeNode.level==0||treeNode.level==1){
             editStr += "<a style='margin:2px 0px 0px 10px;' id='diyBtn1_" +treeNode.id+ "' onclick='addnode("+treeNode.id+");return false;'>添加</a>";
             editStr += "<a style='margin:2px 0px 0px 5px;' id='diyBtn3_" +treeNode.id+ "' onclick='deletenode("+treeNode.id+");return false;'>删除</a>";
         }
        if(treeNode.level==2){
            editStr += "<a style='margin:2px 0px 0px 10px;' id='diyBtn3_" +treeNode.id+ "' onclick='deletenode("+treeNode.id+");return false;'>删除</a>";
        }

        aObj.after(editStr);
    }
    function addnode(nodeId){
        var node = zTreeObj.getNodeByParam("id",nodeId, null);
        formDataLoad("myform",{"pid":node.id,"sort":0,"name":"","icon_class":'',"href":'',"id":''});
    }
    function deletenode(nodeId){
        var node = zTreeObj.getNodeByParam("id",nodeId, null);
        layer.confirm('确定要删除吗？',{'title':"提示",'icon':0,'closeBtn':0,'shadeClose':true}, function(index){
            $.post("/admin/adminMenu/delete",{
                "id":nodeId
            },function(data){
                if(data.code==1){
                    layer.msg("删除成功!");
                    flushTree();
                }
            });
            layer.close(index);
        });

    }
    $(document).ready(function(){
        flushTree();
    });
</script>
</body>
</html>
