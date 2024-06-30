<?php
$dirname = './src';
$pharFile = './cyqd.phar';
if (ini_get('phar.readonly')) {
  die("\033[31m需要在 php.ini 中将 phar.readonly 属性设置为 false，才能生成 phar\033[0m");
}
echo '生成 phar 中...';
if (file_exists($pharFile)) {
    unlink($pharFile);
}

function getStub()
{
    $stub = <<<'EOF'
#!/usr/bin/env php
<?php
if (!class_exists('Phar')) {
    echo 'PHP 的 phar 扩展缺失，而本程序依赖于它。请启用扩展或重新编译 PHP 而不使用 --disable-phar 参数，然后重试。' . PHP_EOL;
    exit(1);
}
Phar::mapPhar('cyqd.phar');
EOF;

    return $stub . <<<'EOF'
require 'phar://cyqd.phar/init.php';
__HALT_COMPILER();
EOF;
}

$phar = new Phar($pharFile, 0, 'cyqd.phar');
$phar->startBuffering();
$phar->buildFromDirectory($dirname);
$content = file_get_contents($dirname.'/init.php');
$content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
$phar->addFromString('init.php', $content);
$phar->setStub(getStub());
$phar->stopBuffering();
if (!file_exists($pharFile)) {
  echo PHP_EOL."\033[31m失败\033[0m".PHP_EOL;
}
chmod($pharFile,0755);
echo "\033[32m完成\033[0m".PHP_EOL;
?>