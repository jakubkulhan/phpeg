--TEST--
bad sequence 2
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('s = [a-zA-Z][a-zA-Z0-9]*');
var_dump($ok, $result);
--EXPECT--
bool(false)
NULL
