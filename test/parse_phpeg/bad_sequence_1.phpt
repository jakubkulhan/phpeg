--TEST--
bad sequence 1
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('s = ...');
var_dump($ok, $result);
--EXPECT--
bool(false)
NULL
