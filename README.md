```
建blog的人基本上会在wordpress和其他开源博客之间选择.我选择的时候没有什么纠结的,本质上blog还是以内容为主.
typecho够简洁,非常符合我的调性.
但配置typecho环境着实还是踩了很多坑的,下面把环境搭建写了详细的教程,供后来者参考,少走一些弯路.有疑问可以留言,有时间尽量回答.

使用的环境和工具:
阿里云
git
docker
vs code

过程中也学习了git/docker/vscode的使用,有时间把总结写出来和大家分享.

以下是整个安装过程: 
```

# tylnmp

# 命令:
## nginx+php
```
docker pull richavey/nginx-php-fpm
docker run -p 80:80 -p 443:443 --name myweb -v $PWD/www/html:/var/www/html -d richarvey/nginx-php-fpm
```
## mysql
```
docker pull mysql
docker run -p 3306:3306 --name mymysql -v /opt/data/mysql:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=your_password -d mysql
```
_其中your_password是你自己定义的_
## 数据库注意事项
`数据挂在文件夹外面比较好`

`数据库要进去启动才能用`
```
docker exec -it mymysql /bin/bash
mysql -uroot -p

按ctrl + p + q退出
```
`数据库typecho是要先建立才能安装的,因为typecho是不会自己新建数据库的`

`数据库的宿主机文件夹里不能有文件`

`如果是本地开发,使用docker inspecter (id后两位) 查看ip地址,网关就是宿主机的


## 修改ngninx/php配置
* 进入nginx容器的bash
```
docker exec -it myweb /bin/bash
```
* vi打开设置文件
```
vi /etc/nginx/sites-enabled/default.conf
```
* 修改里面的配置
**server里增加下面的代码**
```
if (-f $request_filename/index.html){
    rewrite (.*) $1/index.html break;
}
if (-f $request_filename/index.php){
    rewrite (.*) $1/index.php;
}
if (!-f $request_filename){
    rewrite (.*) /index.php;
}
```
**修改location**
```
location ~ .*\.php(\/.*)*$ {
                try_files $uri =404;
                #fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_split_path_info ^(.+?\.php)(/.*)$;
                fastcgi_pass unix:/var/run/php-fpm.sock;
                fastcgi_param PATH_INFO $fastcgi_path_info;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name
                fastcgi_param SCRIPT_NAME $fastcgi_script_name;
                fastcgi_index index.php;
                include fastcgi_params;
}
```
**另一种方式**
```
location ~ \.php$ {
        try_files $uri = 404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
```
**参考配置文件**
```
server {
    listen 80;
    server_name polarxiong.com www.polarxiong.com polarxiong.me;
    root /usr/share/nginx/html/blog;
    index index.php index.html index.htm;

    location / {
         index index.html index.php;
        if (-f $request_filename/index.html){
            rewrite (.*) $1/index.html break;
        }
        if (-f $request_filename/index.php){
            rewrite (.*) $1/index.php;
        }
        if (!-f $request_filename){
            rewrite (.*) /index.php;
        }
        # First attempt to serve request as file, then
        # as directory, then fall back to displaying a 404.
        try_files $uri $uri/ =404;
        # Uncomment to enable naxsi on this location
        # include /etc/nginx/naxsi.rules
    }
    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/html;
    }

    location ~ \.php$ {
        try_files $uri = 404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```
`加入php.ini`
```
docker exec -it myweb /bin/bash  进入容器
cd    找到php.ini-develop copy一份
php -i |grep php.ini 
```
```
exit 退出容器

docker restart myweb 重启容器
```
