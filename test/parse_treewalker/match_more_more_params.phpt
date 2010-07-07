--TEST--
match more more params
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('hello, world, abc (a, b, c) -> array($c, $b, $a)');
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
      array(3) {
        [0]=>
        string(1) "a"
        [1]=>
        string(1) "b"
        [2]=>
        string(1) "c"
      }
      [3]=>
      string(25) "return array($c, $b, $a);"
    }
  }
}
