--TEST--
match anything 1 param
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('_ (a) -> $a');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(10) "treewalker"
  [1]=>
  array(1) {
    [0]=>
    array(4) {
      [0]=>
      string(7) "matcher"
      [1]=>
      bool(true)
      [2]=>
      array(1) {
        [0]=>
        string(1) "a"
      }
      [3]=>
      string(10) "return $a;"
    }
  }
}
