--TEST--
invoke declaration, 1 param
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-invoke (a) {return NULL;}    _ -> NULL');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(10) "treewalker"
  [1]=>
  array(2) {
    [0]=>
    array(3) {
      [0]=>
      string(6) "invoke"
      [1]=>
      array(1) {
        [0]=>
        string(1) "a"
      }
      [2]=>
      string(12) "return NULL;"
    }
    [1]=>
    array(4) {
      [0]=>
      string(7) "matcher"
      [1]=>
      bool(true)
      [2]=>
      array(0) {
      }
      [3]=>
      string(12) "return NULL;"
    }
  }
}
