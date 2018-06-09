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
</head>
<body class="main_body">
<div class="layui-layout layui-layout-admin">
    @include("admin/header")
    <!-- 左侧导航 -->
    @include("admin/left")
    <!-- 右侧内容 -->
    <div class="layui-body layui-form">
        <div class="layui-tab marg0">
            <ul class="layui-tab-title">
                <li class="layui-this"><cite>后台首页</cite></li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show" style="padding:10px;">
                    <div class="panel_box row">
                        <div class="panel col">
                            <a href="javascript:;" data-url="page/user/allUsers.html">
                                <div class="panel_icon" style="background-color:#F0D879;">
                                    <i class="layui-icon" data-icon="&#xe613;">&#xe613;</i>
                                </div>
                                <div class="panel_word userAll">
                                    <span>3</span>
                                    <cite>新增人数</cite>
                                </div>
                            </a>
                        </div>
                        <div class="panel col">
                            <a href="javascript:;" data-url="page/img/images.html">
                                <div class="panel_icon" style="background-color:#248888;">
                                    <i class="iconfont icon-feiyongshenqing"></i>
                                </div>
                                <div class="panel_word imgAll">
                                    <span>4</span>
                                    <cite>交易金额</cite>
                                </div>
                            </a>
                        </div>
                        <div class="panel col">
                            <a href="javascript:;" data-url="page/img/images.html">
                                <div class="panel_icon" style="background-color:#A099FF;">
                                    <i class="iconfont icon-jifendingdan"></i>
                                </div>
                                <div class="panel_word imgAll">
                                    <span>4</span>
                                    <cite>下单次数</cite>
                                </div>
                            </a>
                        </div>
                        <div class="panel col">
                            <a href="javascript:;" data-url="page/img/images.html">
                                <div class="panel_icon" style="background-color:#E7475E;">
                                    <i class="iconfont icon-kehuguanli"></i>
                                </div>
                                <div class="panel_word imgAll">
                                    <span>4</span>
                                    <cite>激活总人数</cite>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="sysNotice col">
                            <blockquote class="layui-elem-quote explain">
                                <p><h3>相关地址</h3></p>
                                <p>
                                    github : <a href="https://github.com/iti6/xie_admin" target="_blank">https://github.com/iti6/xie_admin</a><br>
                                    项目介绍网址 : <a href="http://www.iti6.com" target="_blank">http://www.iti6.com</a>
                                </p>
                            </blockquote>
                        </div>
                        <div class="sysNotice col">
                            <blockquote class="layui-elem-quote title">系统基本参数</blockquote>
                            <table class="layui-table">
                                <colgroup>
                                    <col width="150">
                                    <col>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>当前版本</td>
                                    <td class="version">V1.0</td>
                                </tr>
                                <tr>
                                    <td>应用名称</td>
                                    <td class="author">{{env('APP_NAME')}}</td>
                                </tr>
                                <tr>
                                    <td>laravel版本</td>
                                    <td class="server">5.5</td>
                                </tr>
                                <tr>
                                    <td>ui框架</td>
                                    <td class="server">layui</td>
                                </tr>
                                <tr>
                                    <td>推荐服务器环境</td>
                                    <td class="server">nginx+php7.1+mysql5.6</td>
                                </tr>
                                <tr>
                                    <td>作者网址</td>
                                    <td class="homePage">www.iti6.com</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部 -->
    @include("admin/footer")
</div>

</body>
</html>
