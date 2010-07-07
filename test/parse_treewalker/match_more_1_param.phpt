--TEST--
match more 1 param
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('hello, world, abc (a) -> $a');
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
      array(3) {
        [0]=>
        string(5) "hello"
        [1]=>
        string(5) "world"
        [2]=>
        string(3) "abc"
      }
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
