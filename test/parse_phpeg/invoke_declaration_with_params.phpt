--TEST--
invoke declaration with params
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-invoke (param1, param2) {return NULL;}    s = .');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(5) "phpeg"
  [1]=>
  array(2) {
    [0]=>
    array(3) {
      [0]=>
      string(6) "invoke"
      [1]=>
      array(2) {
        [0]=>
        string(6) "param1"
        [1]=>
        string(6) "param2"
      }
      [2]=>
      string(12) "return NULL;"
    }
    [1]=>
    array(3) {
      [0]=>
      string(4) "rule"
      [1]=>
      string(1) "s"
      [2]=>
      array(1) {
        [0]=>
        string(3) "any"
      }
    }
  }
}
