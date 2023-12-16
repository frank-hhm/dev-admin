

dev-admin
===============

## 特性

* PHP8 thinkphp8 mysql
* vue3 typescript vite arco-design

## 服务端安装

~~~
composer require
~~~

导入数据库

~~~
php think migrate:run
~~~


启动服务

~~~
php think run
~~~

## web安装

~~~
yarn install
~~~
启动服务

~~~
yarn dev || yarn build
~~~

## Nginx配置

~~~
location / {
  if (!-e $request_filename){
  	rewrite ^/(.*)$ /index.php/$1 last;
  	break;
  }
}
~~~