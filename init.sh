#!/bin/bash
cp .env.example .env && vim .env  #建立配置文件并编辑
composer install #安装composer
chmod -R 0777 storage
chmod -R 0777 bootstrap/cache  #文件写入权限
php artisan key:generate  #初始化秘钥
php artisan migrate  #建表
php artisan db:seed  #初始化数据
php artisan storage:link #建立文件软连接

#启动队列进程
default=`ps -ef | grep queue=high,default | grep -v "grep" | wc -l`
if [ $default -gt 0 ]; then
 echo "default queue is running..."
else
 nohup php artisan queue:work --queue=high,default --tries=3 >/dev/null 2>&1 &  #开启队列
 echo "default queue started"
fi

wx=`ps -ef | grep queue=wx | grep -v "grep" | wc -l`
if [ $wx -gt 0 ]; then
 echo "wx queue is running..."
else
 nohup php artisan queue:work --queue=wx --tries=3 >/dev/null 2>&1 &  #开启队列
 echo "wx queue started"
fi

