# 慈云数据自动登录签到程序
**由于慈云官网给登录页加上了人机验证，加上签到功能被下架，该程序已无法使用。**
## 构建
构建之前，需要安装PHP（>=7.0）和Composer。  
构建命令：
```bash
cd src
composer install
cd ..
php build.php
```
## 使用
可从[此处](https://github.com/yucho123987/cyqd/releases)下载最新版本的程序，也可自行构建。  
启动之前，需要安装PHP（>=7.0）和Curl拓展。  
启动程序命令：
```bash
php cyqd.phar
```
或者：
```bash
chmod +x cyqd.phar
./cyqd.phar
```
启动程序后，输入自己的邮箱和密码（目前仅支持邮箱+密码登录），登录后将自动签到。要实现每天定时自动签到，需要借助Cron任务。