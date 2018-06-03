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
                @if(!empty($show)&&$show)
                    <li class="layui-this"><cite>编辑</cite></li>
                @else
                    <li class="layui-this"><cite>查看</cite></li>
                @endif
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                        <form class="layui-form" action="{{url("/admin/roleFunctions/add")}}" method="post">
                        <input hidden name="id" value="{{$roleFunctions->id or ''}}" />
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">角色id</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="role_id" value="{{$roleFunctions->role_id or ''}}" class="layui-input newsName" lay-verify="required" placeholder="角色id">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">权限id</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="functions_id" value="{{$roleFunctions->functions_id or ''}}" class="layui-input newsName" lay-verify="required" placeholder="权限id">
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
