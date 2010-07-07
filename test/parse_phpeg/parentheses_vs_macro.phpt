--TEST--
parentheses vs macro
--FILE--
<?php
require_once dirname(__FILE__) . '/bootstrap.php';

list($ok, $result, $errinfo) = $parser->parse('s = p a r e n (t h e s e s) m(a c r o)');
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
        string(3) "all"
        [1]=>
        array(7) {
          [0]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "p"
          }
          [1]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "a"
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
            string(1) "e"
          }
          [4]=>
          array(2) {
            [0]=>
            string(5) "apply"
            [1]=>
            string(1) "n"
          }
          [5]=>
          array(2) {
            [0]=>
            string(3) "all"
            [1]=>
            array(6) {
              [0]=>
              array(2) {
                [0]=>
                string(5) "apply"
                [1]=>
                string(1) "t"
              }
              [1]=>
              array(2) {
                [0]=>
                string(5) "apply"
                [1]=>
                string(1) "h"
              }
              [2]=>
              array(2) {
                [0]=>
                string(5) "apply"
                [1]=>
                string(1) "e"
              }
              [3]=>
              array(2) {
                [0]=>
                string(5) "apply"
                [1]=>
                string(1) "s"
              }
              [4]=>
              array(2) {
                [0]=>
                string(5) "apply"
                [1]=>
                string(1) "e"
              }
              [5]=>
              array(2) {
                [0]=>
                string(5) "apply"
                [1]=>
                string(1) "s"
              }
            }
          }
          [6]=>
          array(3) {
            [0]=>
            string(6) "expand"
            [1]=>
            string(1) "m"
            [2]=>
            array(1) {
              [0]=>
              array(2) {
                [0]=>
                string(3) "all"
                [1]=>
                array(4) {
                  [0]=>
                  array(2) {
                    [0]=>
                    string(5) "apply"
                    [1]=>
                    string(1) "a"
                  }
                  [1]=>
                  array(2) {
                    [0]=>
                    string(5) "apply"
                    [1]=>
                    string(1) "c"
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
                }
              }
            }
          }
        }
      }
    }
  }
}
