--TEST--
bad namespace declaration
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-namespacen\\a\\m\\e\\s\\p\\a\\c\\e    s = .');
var_dump($ok, $result);
--EXPECT--
bool(false)
NULL
