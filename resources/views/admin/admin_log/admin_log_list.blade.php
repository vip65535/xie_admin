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
                <li class="layui-this"><cite>管理员日志管理</cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show" style="padding:20px;">
                    <!--主体开始-->
                    <form id="myform" action="{{url('adminLog/lists')}}" method="get">
                    <blockquote class="layui-elem-quote news_search">
                        <input type="text" id="curr_p" name="p" value="1" hidden />
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input type="text" name="aid" value="{{$aid or ''}}" placeholder="请输入管理员id" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="method" value="{{$method or ''}}" placeholder="请输入访问类型" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="url" value="{{$url or ''}}" placeholder="请输入访问链接" class="layui-input search_input">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="setime" value="{{$setime or ''}}" class="layui-input" id="test10" placeholder="开始结束时间">
                            </div>
                            <a class="layui-btn search_btn" onclick="mysearch();"><i class="iconfont icon-sousuo"></i>查询</a>
                            @if(\App\Model\Admin::isAuth("/admin/adminLog/export"))
                            <a href="javascript:exportTable();" class="layui-btn search_btn" ><i class="iconfont icon-xiazai"></i>导出</a>
                            @endif
                            @if(\App\Model\Admin::isAuth("/admin/adminLog/add"))
                            <a href="{{url('admin/adminLog/add')}}" class="layui-btn search_btn"><i class="iconfont icon-tianjia"></i>添加</a>
                            @endif
                        </div>
                    </blockquote>
                    </form>
                    <div class="layui-form links_list">
                        <table class="layui-table">
                            <thead>
                            <tr>
                            <th>id</th>
                            <th>管理员</th>
                            <th>访问类型</th>
                            <th>访问链接</th>
                            <th>请求数据</th>
                            <th>访问时间</th>
                            <th>操作</th>
                            </tr>
                            </thead>
                            <tbody class="links_content">
                            @foreach($list as $itme)
                            <tr>
                                <td>{{$itme->id}}</td>
                                <td>{{$itme->aid}}</td>
                                <td>{{$itme->method}}</td>
                                <td>{{$itme->url}}</td>
                                <td style="max-width:200px;max-height:200px;overflow: scroll;">{{$itme->param}}</td>
                                <td>{{$itme->created_at}}</td>
                                <td nowrap="true">
                                    @if(\App\Model\Admin::isAuth("/adminLog/show"))
                                        <a href="{{url('/adminLog/show?id='.$itme->id)}}" ><i class="iconfont icon-dayinmoban"></i> 查看</a>
                                    @endif
                                    @if(\App\Model\Admin::isAuth("/admin/adminLog/edit"))
                                    <a href="{{url('/adminLog/edit?id='.$itme->id)}}" ><i class="iconfont icon-xuqiudengji"></i> 编辑</a>
                                    @endif
                                    @if(\App\Model\Admin::isAuth("/adminLog/delete"))
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
            $.post("{{url('adminLog/delete')}}",{
                "id":id,
            },function(data){
                if(data.code==1){
                    location.href ="{{url('adminLog/lists')}}";
                }
            },"json")
            layer.close(index);
        });
    }
    function exportTable()
    {
        var param = $("#myform").serialize()
        window.open("{{url('adminLog/export')}}?"+param);
        return;
    }
</script>
</body>
</html>
