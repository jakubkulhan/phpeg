--TEST--
bad name declaration
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-namemynaughtytreewalker    _ -> NULL');
var_dump($ok, $result);
--EXPECT--
bool(false)
NULL
