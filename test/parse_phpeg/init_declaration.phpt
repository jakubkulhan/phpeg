--TEST--
init declaration
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-init {$hello = "world";}    s = .');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(5) "phpeg"
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
