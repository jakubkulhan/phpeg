--TEST--
macro definition 0 params
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('mymacro() = "mymacro"');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(5) "phpeg"
  [1]=>
  array(1) {
    [0]=>
    array(4) {
      [0]=>
      string(5) "macro"
      [1]=>
      string(7) "mymacro"
      [2]=>
      array(0) {
      }
      [3]=>
      array(2) {
        [0]=>
        string(7) "literal"
        [1]=>
        string(7) "mymacro"
      }
    }
  }
}
