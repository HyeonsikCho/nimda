#! /usr/local/bin/php -f
<?
include_once('/home/dprinting/nimda/engine/common/ConnectionPool.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fd = fopen('/home/dprinting/nimda/aft_data.csv', 'r');

if ($fd === false) {
    echo "false\n";
    exit;
}

$query = "INSERT INTO prdt_after(after_name, depth1, depth2, depth3) VALUES('%s', '%s', '%s', '%s')";

$i = 0;
while (feof($fd) === false) {
    $buf = fgets($fd, 4096);
    $buf = preg_replace("/\r\n/i", "", $buf);
    $buf = explode(',', $buf);

    $name = $buf[0];
    $d1   = $buf[1];
    $d2   = $buf[2];
    $d3   = $buf[3];

    $q = sprintf($query, $name, $d1, $d2, $d3);

    echo $i++ . " = $q \n";

    $conn->Execute($q);
}

fclose($fd);

$conn->Close();
?>
