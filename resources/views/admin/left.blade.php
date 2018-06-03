<div class="layui-side layui-bg-black">
    <div class="navBar layui-side-scroll">

        <ul class="layui-nav layui-nav-tree">
            <?php
            $menus = \App\Model\Functions::getMyMenu();
            foreach ($menus as $menu){
                $uri = \Illuminate\Support\Facades\Request::getRequestUri();
                if(count($menu->child)>0){
                    $selected = '';
                    foreach ($menu->child as $child){
                        if(explode("/",$uri)[1]==explode("/",$child->href)[1]){
                            $selected = 'layui-nav-itemed';
                        }
                    }
                    echo '<li class="layui-nav-item '.$selected.'">';
                    echo '<a href="javascript:;"><i class="iconfont '.$menu->icon_class.'"></i><cite>'.$menu->name.'</cite><span class="layui-nav-more"></span></a>';
                    echo '<dl class="layui-nav-child">';
                    foreach ($menu->child as $child){
                        if(explode("/",$uri)[1]==explode("/",$child->href)[1]){
                                echo '<dd class="layui-this"><a href="'.$child->href.'"><i class="iconfont '.$child->icon_class.'"></i><cite>'.$child->name.'</cite></a></dd>';
                            }else{
                                echo '<dd><a href="'.$child->href.'"><i class="iconfont '.$child->icon_class.'"></i><cite>'.$child->name.'</cite></a></dd>';
                            }
                        }
                    echo '</dl>';
                    echo '</li>';
                }else{
                    if(explode("/",$uri)[1]==explode("/",$menu->href)[1]){
                        echo '<li class="layui-nav-item layui-this">';
                    }else{
                        echo '<li class="layui-nav-item">';
                    }
                    echo '<a href="'.$menu->href.'"><i class="iconfont '.$menu->icon_class.'" ></i><cite>'.$menu->name.'</cite></a>';
                    echo '</li>';
                }
            }
            ?>

{{--
            <li class="layui-nav-item layui-nav-itemed">
                <a><i class="iconfont icon_class-xitongpeizhi"></i><cite>系统管理</cite><span class="layui-nav-more"></span></a>
                <dl class="layui-nav-child">
                    <dd><a href="{{url("admin/index")}}"><i class="iconfont icon_class-xitongcanshushezhi"></i><cite>系统设置</cite></a></dd>
                    <dd class="layui-this">
                        <a href="{{url("admin/index")}}"><i class="iconfont icon_class-kehuguanli"></i><cite>管理员</cite></a>
                    </dd>

                </dl>
            </li>
--}}



        </ul>
    </div>

</div>