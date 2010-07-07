--TEST--
import declaration, source file
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-import . "file.phpeg"    s = .');
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
      string(6) "import"
      [1]=>
      bool(true)
      [2]=>
      string(10) "file.phpeg"
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
