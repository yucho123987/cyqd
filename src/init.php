<?php
define('CYQD_VERSION_NAME','v1.0.0');
define('CYQD_VERSION_CODE',1);
date_default_timezone_set('Asia/Shanghai');
require __DIR__ . '/vendor/autoload.php';
require 'functions.php';
use Curl\Curl;
$cookie=array();
$update_curl= new Curl();
$init_log=new Log('init',$argv);
$init_log->info('当前工作目录：'.getcwd());
$init_log->info('当前版本：'.CYQD_VERSION_NAME);
$init_log->info('检测更新中...');
$update_curl->get('https://wds.ecsxs.com/231830.json');
if (!$update_curl->error) {
  $update=$update_curl->response;
  if ($update->code>CYQD_VERSION_CODE) {
    $init_log->info('检测到新版本（'.$update->name.'）');
    println('更新介绍：'.$update->description);
    println('下载链接：'.$update->download_url);
    $init_log->info('5秒后开始初始化...');
    sleep(5);
  } else {
    $init_log->info('当前已是最新版本（'.CYQD_VERSION_NAME.'）');
  }
} else {
  $init_log->warn('检测更新失败：'.$update_curl->errorMessage);
}
$config_file=getcwd().'/data.json';
get_token:
$init_log->info('获取token中...');
$token_curl=new Curl();
$token_curl->setReferrer('https://www.zovps.com/');
$token_curl->setHeader('Origin','https://www.zovps.com');
$token_curl->get('https://www.zovps.com/login');
if ($token_curl->error) {
  $init_log->error('请求失败：'.$token_curl->errorMessage);
  $init_log->info('5秒后重试...');
  sleep(5);
  goto get_token;
}
$cookie=$token_curl->responseCookies;
//检测是否可截取token
$temp_114=explode('name="token" value="',$token_curl->response,2);
if (count($temp_114)<2) {
  $init_log->error('未找到token截取起点，可能需要更新程序');
  exit();
}
$temp_514=explode('"',$temp_114[1],2);
if (count($temp_514)<2) {
  $init_log->error('未找到token截取终点，可能需要更新程序');
  exit();
}
$token=$temp_514[0];
$init_log->info('获取到的token：'.$token);
if (!file_exists($config_file)) {
  $init_log->info('未在工作目录发现数据文件');
  if (is_writable(getcwd())) {
    $init_log->info('工作目录可写，可正常创建数据文件');
    println('——————————————————登录——————————————————');
    input_email:
    $email=read('邮箱：');
    if ($email==''||!filter_var($email,FILTER_VALIDATE_EMAIL)) {
      println('请输入有效的邮箱地址');
      goto input_email;
    }
    input_password:
    $password=read('密码：');
    if ($password=='') {
      println('请输入密码');
      goto input_password;
    }
  } else {
    $init_log->error('无工作目录修改权限，无法创建数据文件');
    exit();
  }
} else {
  $data=json_decode(@file_get_contents(getcwd().'/data.json'),true);
  if (is_null($data)) {
    $init_log->error('解析数据文件失败，请删除后重试');
    exit();
  }
  if (!array_key_exists('email',$data)) {
    $init_log->error('数据文件无帐号邮箱，请删除后重试');
    exit();
  }
  if (!array_key_exists('password',$data)) {
    $init_log->error('数据文件无帐号密码，请删除后重试');
    exit();
  }
  $email=$data['email'];
  $password=$data['password'];
}
require('login.php');
?>