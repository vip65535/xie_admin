
# About xie_admin

## 一个只有基础框架的后台管理系统，基于laravel5.5 与Layui2.3
>
开发者博客：http://www.iti6.com
>
演示站：http://testadmin.iti6.com

## 搭建步骤

>1. clone 工程
>2. composer install && php artisan key:generate
>3. 导入data_init.sql
>4. 修改本地.env文件（默认配置）
>5. 后台权限账号默认基126邮箱验证,如需更换请自行修改pop3地址与端口
>6. 首次登录，可用账户：test  密码：test 进行登录

## 二次开发
>1. 项目中目前已有权限和功能控制模块。现以在左侧菜单栏中添加学生管理为例，描述自创建模版的使用
>2. 在数据库中添加students表，添加 id、name、grade、updated_at（必需字段）、created_at（必需字段），并标明字段描述，填入至少一条记录
>3. 执行 php artisan xiely:make students,会在Controller 和 Model中分别生成students表对应的代码
>4. 在系统管理-权限节点中编辑上面新添加的模块，功能名称设置为"学生管理"


## 演示图片
>
![登录](https://raw.githubusercontent.com/iti6/xie_admin/master/public/images/test/0.png)
>
![主页](https://raw.githubusercontent.com/iti6/xie_admin/master/public/images/test/1.png)
>
![权限](https://raw.githubusercontent.com/iti6/xie_admin/master/public/images/test/2.png)
>
![管理员日志](https://raw.githubusercontent.com/iti6/xie_admin/master/public/images/test/3.png)
>
![管理员编辑](https://raw.githubusercontent.com/iti6/xie_admin/master/public/images/test/4.png)
