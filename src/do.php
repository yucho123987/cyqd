<?php
use Curl\Curl;
$do_log=new Log('do',$argv);
get_uid:
$do_log->info('获取uid中...');
$uid_curl=new Curl();
$uid_curl->setReferrer('https://www.zovps.com/clientarea');
$uid_curl->setUserAgent('Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36');
$uid_curl->setHeader('Origin','https://www.zovps.com');
$uid_curl->setCookies($cookie);
$uid_curl->get('https://www.zovps.com/addons',[
  '_plugin'=>70,
  '_controller'=>'index',
  '_action'=>'index'
]);
if ($uid_curl->error) {
  $do_log->error('请求失败：'.$uid_curl->errorMessage);
  $do_log->info('5秒后重试...');
  sleep(5);
  goto get_uid;
}
$temp_114=explode('uid = ',$uid_curl->response,2);
if (count($temp_114)<2) {
  $do_log->error('未找到uid截取起点，可能需要更新程序');
  exit();
}
$temp_514=explode(';',$temp_114[1],2);
if (count($temp_514)<2) {
  $do_log->error('未找到uid截取终点，可能需要更新程序');
  exit();
}
$uid=$temp_514[0];
$do_log->info('获取到的uid：'.$uid);
qiandao:
$do_log->info('签到中...');
$qiandao_curl=new Curl();
$qiandao_curl->setCookies($cookie);
$qiandao_curl->setHeader('Origin','https://www.zovps.com');
$qiandao_curl->setReferrer('https://www.zovps.com/clientarea');
$qiandao_curl->setUserAgent('Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36');
$qiandao_curl->post('https://www.zovps.com/addons?_plugin=70&_controller=index&_action=index',[
  'uid'=>$uid
]);
if ($qiandao_curl->error) {
  $do_log->error('请求失败：'.$qiandao_curl->errorMessage);
  $do_log->info('5秒后重试...');
  sleep(5);
  goto qiandao;
}
$result=json_decode($qiandao_curl->response);
if ($result->code!=200) {
  $do_log->error($result->msg);
} else {
  $do_log->info($result->msg);
}
?>