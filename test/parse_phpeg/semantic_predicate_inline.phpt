--TEST--
semantic predicate inline
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('s = ?-> FALSE');
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
        string(18) "semantic_predicate"
        [1]=>
        string(13) "return FALSE;"
      }
    }
  }
}
