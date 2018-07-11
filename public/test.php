<?php
phpinfo();
try {
$dbh = new PDO('mysql:host=localhost;dbname=common', 'root', 'root');

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}