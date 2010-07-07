--TEST--
namespace declaration
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('-namespace n\\a\\m\\e\\s\\p\\a\\c\\e    _ -> NULL');
var_dump($ok, $result);
--EXPECT--
bool(true)
array(2) {
  [0]=>
  string(10) "treewalker"
  [1]=>
  array(2) {
    [0]=>
    array(2) {
      [0]=>
      string(9) "namespace"
      [1]=>
      string(17) "n\a\m\e\s\p\a\c\e"
    }
    [1]=>
    array(4) {
      [0]=>
      string(7) "matcher"
      [1]=>
      bool(true)
      [2]=>
      array(0) {
      }
      [3]=>
      string(12) "return NULL;"
    }
  }
}
