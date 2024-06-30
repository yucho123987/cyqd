<?php
use Curl\Curl;
// 日志记录类
#[\AllowDynamicProperties]
class Log {
  private $log_dir;
  private $name;
  private $log_file='/applogs.txt';
  public function __construct($name,$arg) {
    $this->log_dir=getcwd();
    $this->name=$name;
    $this->argv=$arg;
  }
  
function error($msg) {
  if (is_writable($this->log_dir)&&in_array('-s',$this->argv)) {
    file_put_contents($this->log_dir.$this->log_file,date('[Y-m-d H:i:s:').explode(' ',microtime())[0].']['.$this->name.'.Error]'.$msg.PHP_EOL,FILE_APPEND);
  }
  echo '['.date('Y-m-d H:i:s')."]\033[31m".$msg."\033[0m".PHP_EOL;
}
function warn($msg) {
  echo '['.date('Y-m-d H:i:s')."]\033[33m".$msg."\033[0m".PHP_EOL;
  if (is_writable($this->log_dir)&&in_array('-s',$this->argv)) {
    file_put_contents($this->log_dir.$this->log_file,date('[Y-m-d H:i:s:').explode(' ',microtime())[0].']['.$this->name.'.Warning]'.$msg.PHP_EOL,FILE_APPEND);
  }
}
function info($msg) {
  echo '['.date('Y-m-d H:i:s')."]\033[35m".$msg."\033[0m".PHP_EOL;
  if (is_writable($this->log_dir)&&in_array('-s',$this->argv)) {
    file_put_contents($this->log_dir.$this->log_file,date('[Y-m-d H:i:s:').explode(' ',microtime())[0].']['.$this->name.'.Info]'.$msg.PHP_EOL,FILE_APPEND);
  }
}
}
//输出并换行
function println($msg) {
  echo $msg.PHP_EOL;
}
//读取一行输入
function read($prompt) {
  echo $prompt;
  return explode(PHP_EOL,fgets(STDIN))[0];
}
?>