#!/bin/bash
composer update #更新composer
php artisan migrate:reset #初始化表结构
php artisan migrate  #建表
php artisan db:seed  #初始化数据

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
