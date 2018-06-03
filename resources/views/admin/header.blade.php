<div class="layui-header header">
    <div class="layui-main">
        <a href="#" class="logo">{{env('APP_NAME')}}</a>
        <!-- 显示/隐藏菜单 -->
        <a href="javascript:;" class="iconfont hideMenu icon-zhankai"></a>
        <!-- 顶部右侧菜单 -->
        <ul class="layui-nav top_menu">
            <li class="layui-nav-item" pc>
                <a href="javascript:;">
                    <cite>{{session('admin')->nickname}}</cite>
                </a>
                <dl class="layui-nav-child">
                   {{-- <dd><a href="{{url("admin/admin")}}">
                            <i class="iconfont icon-zhanghu" data-icon="icon-zhanghu"></i><cite>个人资料</cite>
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" >
                            <i class="iconfont icon-shezhi1" data-icon="icon-shezhi1"></i><cite>修改密码</cite>
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" class="changeSkin">
                            <i class="iconfont icon-huanfu"></i><cite>更换皮肤</cite>
                        </a>
                    </dd>--}}
                    <dd>
                        <a href="{{url('/loginOut')}}" class="signOut">
                            <i class="iconfont icon-loginout"></i><cite>退出</cite>
                        </a>
                    </dd>
                </dl>
            </li>
        </ul>
    </div>
</div>
<script>
    layui.use(['element','laypage','layer','laydate','form'], function(){
        var element = layui.element;
        var from = layui.form;
        element.render();
        from.render();

    });

</script>