--TEST--
macro definition more params
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('mymacro(w, o, r, l, d) = d l r o w');
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
      array(2) {
        [0]=>
        string(3) "all"
        [1]=>
        array(5) {
          [0]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "d"
          }
          [1]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "l"
          }
          [2]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "r"
          }
          [3]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "o"
          }
          [4]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "w"
          }
        }
      }
    }
  }
}
