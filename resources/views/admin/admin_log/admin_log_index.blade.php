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
                @if(strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"add"))
                    <li class="layui-this"><cite>添加</cite></li>
                @endif
                @if(strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"edit"))
                    <li class="layui-this"><cite>编辑</cite></li>
                @endif
                @if(strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"show"))
                    <li class="layui-this"><cite>查看</cite></li>
                @endif
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                    @if(strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"add"))
                        <form id="myForm" class="layui-form layui-form-pane" action="add" method="post">
                    @endif
                    @if(strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"edit"))
                        <form id="myForm" class="layui-form layui-form-pane" action="edit" method="post">
                    @endif

                        <input hidden name="id" value="{{$adminLog->id or ''}}" />
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">管理员</label>
                                        <div class="layui-input-block">
                                            <input type="text" style="width:400px;" name="aid" value="{{$adminLog->aid or ''}}" class="layui-input newsName" lay-verify="required" placeholder="管理员">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">访问类型</label>
                                        <div class="layui-input-block">
                                            <input type="text" style="width:400px;" name="method" value="{{$adminLog->method or ''}}" class="layui-input newsName" lay-verify="required" placeholder="访问类型">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">访问链接</label>
                                        <div class="layui-input-block">
                                            <input type="text" style="width:400px;" name="url" value="{{$adminLog->url or ''}}" class="layui-input newsName" lay-verify="required" placeholder="访问链接">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">请求数据</label>
                                        <div class="layui-input-block">
                                            <input type="text" style="width:400px;" name="param" value="{{$adminLog->param or ''}}" class="layui-input newsName" lay-verify="required" placeholder="请求数据">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">IP地址</label>
                                        <div class="layui-input-block">
                                            <input type="text" style="width:400px;" name="ip" value="{{$adminLog->ip or ''}}" class="layui-input newsName" lay-verify="required" placeholder="IP地址">
                                        </div>
                                    </div>

                            <div class="layui-form-item">
                            <div class="layui-input-block">
                                @if(!strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"show"))
                                <button class="layui-btn" lay-submit id="addbutton" lay-filter="addbutton">提交</button>
                                @endif
                                <button type="reset" onclick="javascript:history.go(-1);" class="layui-btn layui-btn-primary">返回</button>
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
    layui.use('form', function () {
        var form = layui.form;
@if(strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"add"))
        var path ="add";
@endif
@if(strpos(\Illuminate\Support\Facades\Request::getPathInfo(),"edit"))
        var path ="edit";
@endif
        form.on('submit(addbutton)', function(data){
            $.ajax({
                dataType: "json",
                url:path,
                type:"post",
                data:data.field,
                beforeSend: function() {
                    $("#addbutton").attr("disabled",true);
                    $("#addbutton").addClass("layui-btn-disabled");
                },
                success:function(data){
                    layer.msg(data.m);
                    if(data.code){
                        window.location.href ="lists";
                    }else{
                        $("#addbutton").removeAttr("disabled");
                        $("#addbutton").removeClass("layui-btn-disabled");
                    }
                    return false;
                },error:function(data){
                    $("#addbutton").removeAttr("disabled");
                    $("#addbutton").removeClass("layui-btn-disabled");
                    layer.msg("服务器异常,稍后再试！");
                    return false;

                }
            });
            return false;
        });
    })
</script>
</body>
</html>
