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
                    <form id="myForm" class="layui-form layui-form-pane" action="#" method="post">
                        <input hidden name="id" value="{{$admin->id or ''}}"/>
                        <div class="layui-form-item">
                            <label class="layui-form-label">用户名</label>
                            <div class="layui-input-block" style="width:200px;">
                                <input type="text" name="user_name" value="{{$admin->user_name or ''}}" class="layui-input newsName" lay-verify="required" placeholder="用户名">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">真实姓名</label>
                            <div class="layui-input-block">
                                <input type="text" name="nickname" style="width:200px;" value="{{$admin->nickname or ''}}"
                                       class="layui-input newsName" lay-verify="required" placeholder="真实姓名">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所在部门</label>
                            <div class="layui-input-block">
                                <input type="text" name="department" style="width:200px;" value="{{$admin->department or ''}}"
                                       class="layui-input newsName" lay-verify="required" placeholder="所在部门">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">状态值</label>
                                <div class="layui-input-block">
                                    <select name="status" class="newsLook" lay-filter="browseLook">
                                        @foreach(\App\Model\Admin::$STATUS  as $k=>$v)
                                            <option value="{{$k}}" <?php if (!empty($admin) && $k == $admin["status"]) {
                                                echo "selected";
                                            } ?> >{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <select name="sex" class="newsLook" lay-filter="browseLook">
                                        @foreach(\App\Model\Admin::$SEX  as $k=>$v)
                                            <option value="{{$k}}" <?php if (!empty($admin) && $k == $admin["sex"]) {
                                                echo "selected";
                                            } ?> >{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">角色</label>
                                <div class="layui-input-block">
                                    @php
                                        $myroles = array();
                                        if(!empty($admin)&&!empty($admin->id)){
                                            $myroles =  \App\Model\AdminRole::getByAdminId($admin->id)->toArray();
                                        }
                                        $roles = \App\Model\Role::all();
                                        foreach ($roles as $role){
                                        if(in_array($role->id,array_column($myroles,"role_id"))){
                                            echo ' <input type="checkbox" checked name="role[]" value="'.$role->id.'" lay-skin="primary" title="'.$role->name.'">';
                                        }else{
                                             echo ' <input type="checkbox" name="role[]" value="'.$role->id.'" lay-skin="primary" title="'.$role->name.'">';
                                        }

                                        }

                                    @endphp
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit id="addbutton" lay-filter="addbutton">提交</button>
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
   layui.use('form', function () {
        var form = layui.form;
        form.on('submit(addbutton)', function(data){
            $.ajax({
                dataType: "json",
                url:"add",
                type:"post",
                data:data.field,
                beforeSend: function() {
                    $("#addbutton").attr("disabled",true);
                    $("#addbutton").addClass("layui-btn-disabled");
                },
                success:function(data){
                    layer.msg(data.m);
                    if(data.code){
                        window.location.href ="{{url('admin/lists')}}";
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
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    })
</script>
</body>
</html>
