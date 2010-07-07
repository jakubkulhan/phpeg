--TEST--
bad import declaration 3
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-import."file.phpeg"    s = .');
var_dump($ok, $result);
--EXPECT--
bool(false)
NULL
