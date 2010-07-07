--TEST--
range 2
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('s = [ \\t\\r\\n]');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(5) "phpeg"
  [1]=>
  array(1) {
    [0]=>
    array(3) {
      [0]=>
      string(4) "rule"
      [1]=>
      string(1) "s"
      [2]=>
      array(2) {
        [0]=>
        string(5) "range"
        [1]=>
        array(4) {
          [0]=>
          int(32)
          [1]=>
          int(9)
          [2]=>
          int(13)
          [3]=>
          int(10)
        }
      }
    }
  }
}
