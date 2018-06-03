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
                                <div class="panel_icon" style="background-color:#009688;">
                                    <i class="layui-icon" data-icon="&#xe613;">&#xe613;</i>
                                </div>
                                <div class="panel_word userAll">
                                    <span>3</span>
                                    <cite>用户总数</cite>
                                </div>
                            </a>
                        </div>
                        <div class="panel col">
                            <a href="javascript:;" data-url="page/img/images.html">
                                <div class="panel_icon" style="background-color:#5FB878;">
                                    <i class="layui-icon" data-icon="&#xe64a;">&#xe64a;</i>
                                </div>
                                <div class="panel_word imgAll">
                                    <span>4</span>
                                    <cite>图片总数</cite>
                                </div>
                            </a>
                        </div>
                    </div>
                    <blockquote class="layui-elem-quote explain">
                        <p>技术交流QQ群：（添加时请注明来自本框架）</p>
                    </blockquote>
                    <div class="row">
                        <div class="sysNotice col">
                            <blockquote class="layui-elem-quote title">最新文章<i class="iconfont icon-new1"></i></blockquote>
                            <table class="layui-table" lay-skin="line">
{{--
                                @php
                                    $rows=\App\Model\Article::getByList(["title"],1,10,array(),"id desc")
                                @endphp
                                @foreach($rows as $row)
                                    <colgroup>
                                        <col><col width="110">
                                    </colgroup>
                                    <tbody class="hot_news">
                                    <tr><td align="left" style="text-align: left;">{{$row->title or ''}}</td><td>2017-04-14</td></tr>

                                    </tbody>
                                @endforeach--}}


                            </table>
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
                                    <td>网站首页</td>
                                    <td class="homePage">xiely.cn</td>
                                </tr>
                                <tr>
                                    <td>服务器环境</td>
                                    <td class="server">{{env('APP_ENV')}}</td>
                                </tr>
                                <tr>
                                    <td>数据库版本</td>
                                    <td class="dataBase">mysql 5.7</td>
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
