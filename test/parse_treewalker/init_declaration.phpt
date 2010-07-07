--TEST--
init declaration
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-init {$hello = "world";}    _ -> NULL');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(10) "treewalker"
  [1]=>
  array(2) {
    [0]=>
    array(2) {
      [0]=>
      string(4) "init"
      [1]=>
      string(17) "$hello = "world";"
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
