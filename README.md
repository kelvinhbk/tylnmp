# tylnmp

# 命令:
## nginx+php
```
docker pull richavey/nginx-php-fpm
docker run -p 8000:80 --name myweb -v $PWD/www/html:/var/www/html -v $PWD/nginx/nginx.conf:/etc/nginx/nginx.conf --link mymysql:db -d richarvey/nginx-php-fpm
```
## mysql
```
docker pull mysql
docker run -p 3306:3306 --name mymysql -v $PWD/data/mysql:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=123456 -d mysql
```

