# rentDIY
cp .env.example .env && vim .env  #建立配置文件并编辑
composer install #安装composer
chmod -R 0777 storage
chmod -R 0777 bootstrap/cache  #文件写入权限
php artisan key:generate  #初始化秘钥
php artisan migrate  #建表
php artisan db:seed  #初始化数据
php artisan storage:link #建立文件软连接