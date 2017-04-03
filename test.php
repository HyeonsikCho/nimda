#! /usr/local/bin/php -f
<?
$name = "/home/dprinting/nimda/test.xlsx";

$ret = is_file($name) ? (@unlink($name) ? true : false) : "err";

var_dump($ret);
?>
