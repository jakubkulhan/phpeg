--TEST--
match more
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('hello, world, abc -> NULL');
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
      array(0) {
      }
      [3]=>
      string(12) "return NULL;"
    }
  }
}
