<?php
use Curl\Curl;
$login_log=new Log('login',$argv);
login:
$login_log->info('登录中...');
$login_curl=new Curl();
$login_curl->setCookies($cookie);
$login_curl->setHeader('Origin','https://www.zovps.com');
$login_curl->setReferrer('https://www.zovps.com/login');
$login_curl->setUserAgent('Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36');
$login_curl->post('https://www.zovps.com/login?action=email',[
  'token'=>$token,
  'email'=>$email,
  'password'=>$password
]);
if ($login_curl->error) {
  $login_log->error('请求失败：'.$login_curl->errorMessage);
  $login_log->info('5秒后重试...');
  sleep(5);
  goto login;
}
$cookie=$login_curl->responseCookies;
$status_code=$login_curl->getHttpStatusCode();
if ($status_code==302) {
  $login_log->info('登录成功');
  $account_login_info=array(
    'email'=>$email,
    'password'=>$password
  );
  file_put_contents(getcwd().'/data.json',json_encode($account_login_info));
} elseif ($status_code==200) {
  $login_log->error('登录失败：用户名或密码错误');
  exit();
} else {
  $login_log->error('登录失败：服务器状态异常（'.$status_code.'）');
  $login_log->info('5秒后重试...');
  sleep(5);
  goto login;
}
require('do.php');
?>