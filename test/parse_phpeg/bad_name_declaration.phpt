--TEST--
bad name declaration
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-namename    s = .');
var_dump($ok, $result);
--EXPECT--
bool(false)
NULL
