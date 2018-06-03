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
                <li class="layui-this"><cite>编辑</cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                        <form class="layui-form" action="{{url("/admin/functions/add")}}" method="post">
                        <input hidden name="id" value="{{$functions->id or ''}}" />
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">排序</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="sort" value="{{$functions->sort or ''}}" class="layui-input newsName" lay-verify="required" placeholder="排序">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">父节点</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="pid" value="{{$functions->pid or ''}}" class="layui-input newsName" lay-verify="required" placeholder="父节点">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">功能名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="name" value="{{$functions->name or ''}}" class="layui-input newsName" lay-verify="required" placeholder="功能名称">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">图标</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="icon_class" value="{{$functions->icon_class or ''}}" class="layui-input newsName" lay-verify="required" placeholder="图标">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">链接</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="href" value="{{$functions->href or ''}}" class="layui-input newsName" lay-verify="required" placeholder="链接">
                                        </div>
                                    </div>
                            <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="addNews">提交</button>
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
    layui.use('form', function() {
        var form = layui.form;
    })

</script>
</body>
</html>
