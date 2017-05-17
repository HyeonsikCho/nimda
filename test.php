#! /usr/local/bin/php -f
<?
class t {
    static function t1() {
        echo "123123123\n";
    }
}

$a = new t();

t::t1();
$a->t1();
?>
