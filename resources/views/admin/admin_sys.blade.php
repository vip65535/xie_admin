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
    @include("admin/static")
    <link rel="stylesheet" href="{{asset('css/admin/main.css')}}" media="all"/>
    <script type="text/javascript" src="{{asset('js/admin/index.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/admin/main.js')}}"></script>
    <style type="text/css">
        .layui-table td, .layui-table th{ text-align: center; }
        .layui-table td{ padding:5px; }
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
                <li class="layui-this"><cite>后台设置</cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                    <form class="layui-form" action="adminSys" method="post">
                        <table class="layui-table">
                            <colgroup>
                                <col width="20%">
                                <col width="50%">
                                <col>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>参数说明</th>
                                <th>参数值</th>
                                <th>变量名</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>网站名称</td>
                                <td><input type="text" class="layui-input" value="{{$sys->web_name}}" name="web_name" lay-verify="required" placeholder="请输入网站名称"></td>
                                <td>web_name</td>
                            </tr>
                            <tr>
                                <td>logo</td>
                                <td>
                                    <input id="logo" value="{{$sys->logo}}" name="logo" hidden>
                                    <div class="layui-upload">
                                        <div class="layui-upload-list" style="float: left;">
                                            <img class="layui-upload-img" src="{{$sys->logo or ''}}" style="float: left;" id="demo1">
                                        </div>
                                        <button type="button" class="layui-btn" style="float: left;" id="test1">上传图片</button>
                                    </div>
                                </td>
                                <td>logo</td>
                            </tr>
                            <tr>
                                <td>网站首页</td>
                                <td><input type="text" class="layui-input" value="{{$sys->web_link}}"  name="web_link" placeholder="请输入网站首页"></td>
                                <td>web_link</td>
                            </tr>
                            <tr>
                                <td>网站关键词</td>
                                <td><input type="text" class="layui-input" value="{{$sys->web_keywords}}"  name="web_keywords" placeholder="请输入网站关键词"></td>
                                <td>web_keywords</td>
                            </tr>
                            <tr>
                                <td>网站描述</td>
                                <td><textarea class="layui-textarea" name="web_description" placeholder="请输入网站描述">{{$sys->web_description}}</textarea></td>
                                <td>web_description</td>
                            </tr>
                            <tr>
                                <td>网站版权</td>
                                <td><input type="text" class="layui-input" value="{{$sys->web_powerby}}"  name="web_powerby" placeholder="请输入网站版权"></td>
                                <td>web_powerby</td>
                            </tr>
                            <tr>
                                <td>网站备案号</td>
                                <td><input type="text" class="layui-input" value="{{$sys->web_icp}}"  name="web_icp" placeholder="请输入网站备案号"></td>
                                <td>web_icp</td>
                            </tr>

                            </tbody>
                        </table>
                        <div class="layui-form-item" style="text-align: right;">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="systemParameter">立即提交</button>
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
    layui.use('upload', function(){
        var $ = layui.jquery
            ,upload = layui.upload;

        //普通图片上传
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: 'upload/upload'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code < 1){
                    return layer.msg('上传失败!');
                }
                $('#demo1').attr('src', res.data.src); //图片链接（base64）
                $('#logo').val(res.data.src);
                //上传成功
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });

    });
</script>

</body>
</html>
