--TEST--
match one more params
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('hello (w, o, r, l, d) -> array($d, $l, $r, $o, $w)');
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
      array(1) {
        [0]=>
        string(5) "hello"
      }
      [2]=>
      array(5) {
        [0]=>
        string(1) "w"
        [1]=>
        string(1) "o"
        [2]=>
        string(1) "r"
        [3]=>
        string(1) "l"
        [4]=>
        string(1) "d"
      }
      [3]=>
      string(33) "return array($d, $l, $r, $o, $w);"
    }
  }
}
