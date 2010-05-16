<?php
class phpgen
{
    /** @var phpeg */
    private $phpeg;

    /** @var string */
    private $generated;

    /** @var array */
    private $methods = array();

    /** @var array */
    private $bind = array();

    /** @var array */
    private $dlr = array();

    /** @return string */
    private function tpl($ns, $inner)
    {
        $inner = $this->i($inner, "            ");
        return <<<E
<?php
// !!! AUTOGENERATED FILE !!!
$ns
function {$this->phpeg->getName()}(\$s, array \$opts = array()) {\n

    if (!class_exists('__{$this->phpeg->getName()}')) {

        class __{$this->phpeg->getName()} {
            var \$_s;
            var \$_p;
            var \$_maxp;
            var \$_expected;
            var \$_memo;

            function __construct(\$_s, array \$opts = array())
            {
                foreach (\$opts as \$n => \$v) { \$this->\$n = \$v; }
                \$this->_s = \$_s;
                \$this->_p = \$this->_maxp = 0;
                \$this->_expected = \$this->_memo = array();
            }

            function _failed(\$s)
            {
                if (\$this->_p > \$this->_maxp) {
                    \$this->_maxp = \$this->_p;
                    \$this->_expected = array();
                }

                if (!in_array(\$s, \$this->_expected)) {
                    \$this->_expected[] = \$s;
                }
            }

            function _getmemo(\$r, \$p)
            {
                \$k = ((string) \$r) . ':' . ((string) \$p);
                if (isset(\$this->_memo[\$k])) {
                    return \$this->_memo[\$k];
                }
                return NULL;
            }

            function _setmemo(\$r, \$p, \$v)
            {
                \$this->_memo[((string) \$r) . ':' . ((string) \$p)] = \$v;
            }

$inner
        }
    }

    \$i = new __{$this->phpeg->getName()}(\$s, \$opts);
    \$ret = \$i->_parse_{$this->phpeg->getStartingRuleName()}();
    return array_merge(\$ret, array(\$i->_maxp, \$i->_expected));
}

E;
    }

    /**
     * Initializes instance
     * @param phpeg
     */
    public function __construct(phpeg $phpeg)
    {
        $this->phpeg = $phpeg;
    }

    /**
     * Generates code
     * @return string
     */
    public function generate()
    {
        if ($this->generated !== NULL) { return $this->generated; }

        $ns = '';
        if ($this->phpeg->getNamespace()) {
            $ns .= "namespace " . $this->phpeg->getNamespace() . ";\n";
        }

        $this->dlr = array_flip($this->phpeg->getDirectlyLeftRecursive());
        $inner = '';
        foreach ($this->phpeg->getRules() as $name => $tree) {
            $inner .= $this->g(0, array('rule', $name, $tree));
        }

        foreach ($this->methods as $method) {
            $inner .= $method;
        }

        return $this->generated = $this->tpl($ns, $inner);
    }

    /** @return string */
    private function i($s, $indent = "    ")
    {
        return $indent . str_replace("\n", "\n" . $indent, rtrim($s, "\n")) . "\n";
    }

    /** @return string */
    private function g($i, $tree)
    {
        $type = array_shift($tree);
        array_unshift($tree, $i);
        return call_user_func_array(array($this, 'g' . $type), $tree);
    }

    /** @return string */
    private function grule($i, $name, $tree)
    {
        $this->bind = array();
        $g = $this->g($i, $tree);
        $safety = !$this->isSafe($tree) ? "// this rule is not safe, cannot set memo: " : "";

        if (isset($this->dlr[$name])) {
            $g = $this->i($g, "    ");

            return <<<E
function _parse_$name() {
    \$m = \$this->_getmemo('$name', \$oldp = \$this->_p);
    if (\$m === NULL) {
        \$this->_setmemo('$name', \$oldp, 0);
        \$newm = array_merge(\$this->_{$name}_(), array(\$newp = \$this->_p));
        \$prevm = \$this->_getmemo('$name', \$oldp);
        \$this->_setmemo('$name', \$oldp, \$newm);

        if (\$prevm === 1) {
            \$stop = \$newp;
            for (;;) {
                \$this->_p = \$oldp;
                \$ans = \$this->_{$name}_();
                \$newp = \$this->_p;
                if (!\$ans[0] || \$newp <= \$stop) { break; }
                \$newm = array_merge(\$ans, array(\$newp));
                \$this->_setmemo('$name', \$oldp, \$newm);
            }
        }

        \$m = \$newm;
        $safety\$this->_setmemo('$name', \$oldp, \$newm);

    } else if (\$m === 0) { // seed
        \$this->_setmemo('$name', \$oldp, 1);
        return array(FALSE, NULL);
    }

    \$this->_p = \$m[2];
    return array(\$m[0], \$m[1]);
}

function _{$name}_() {        
$g
    return \$_$i;
}

E;

        } else {

            $g = $this->i($g, "        ");

            return <<<E
function _parse_$name() {
    \$_m = \$this->_getmemo('$name', \$_oldp = \$this->_p);
    if (\$_m === NULL) {
$g
        $safety\$this->_setmemo('$name', \$_oldp, \$_m = array_merge(\$_$i, array(\$this->_p)));
        return \$_$i;
    }
    \$this->_p = \$_m[2];
    return array(\$_m[0], \$_m[1]);
}

E;
        }
    }

    /** @return string */
    private function gfst()
    {
        $args = func_get_args();
        $i = array_shift($args);

        $myi = $i++;
        $ret = "";
        $ret .= "\$_$myi = array(FALSE, NULL);\n" .
                "\$_pos$myi = \$this->_p;\n" .
                "do {\n";

        foreach ($args as $tree) {
            $ret .= $this->i($this->g($i, $tree));
            $ret .= "    if (!\$_{$i}[0]) {\n" .
                    "        \$this->_p = \$_pos$myi;\n" .
                    "    } else {\n" .
                    "        \$_$myi = \$_$i;\n" .
                    "        break;\n" .
                    "    }\n";
            ++$i;
        }

        $ret .= "} while(0);\n";

        return $ret;
    }

    /** @return string */
    private function gact($i, $code, $tree)
    {
        $treei = $i + 1;
        $bind = $this->bind;

        $ret = $this->g($treei, $tree);

        $ret .= "\$_$i = array(FALSE, NULL);\n" .
                "if (\$_{$treei}[0]) {\n" .
                "    \$_$i = array(TRUE, " . $this->gcode($code) . ");\n" .
                "}\n";

        $this->bind = $bind;

        return $ret;
    }

    /** @return string */
    private function gall()
    {
        $args = func_get_args();
        $i = array_shift($args);
        $firsti = NULL;

        foreach ($args as $k => $t) {
            if ($t[0] === 'bnd') { $t = $t[2]; }
            if ($t[0] === 'and' || $t[0] === 'not' || $t[0] === 'spr') { continue; }
            $firsti = $i + $k;
            break;
        }

        if ($firsti === NULL) { $firsti = $i; }

        $ret = "do {\n";
        $maxk = $i;

        foreach ($args as $k => $tree) {
            $ret .= $this->i($this->g($i + $k, $tree));
            $ret .= "    if (!\$_" . ($i + $k) . "[0]) {\n" .
                    "        \$_$firsti = array(FALSE, NULL);\n" .
                    "        break;\n" . 
                    "    }\n";
            $maxk = $k;
        }

        if ($this->isSimple(array_merge(array('all'), $args))) {
            $vars = array();
            foreach (range($i, $i + $maxk) as $j) { $vars[] = "\$_{$j}[1]"; }
            $ret .= "    \$_$firsti = array(TRUE, implode('', array(" . implode(", ", $vars) . ")));\n";
        }

        $ret .= "} while(0);\n";
        $ret .= (($i !== $firsti) ? "\$_$i = \$_$firsti;\n" : '');

        return $ret;
    }

    /** @return string */
    private function gbnd($i, $varname, $tree)
    {
        $this->bind[$varname] = TRUE;
        return "\$_$i = array(FALSE, NULL);\n" .
               "\$$varname =& \$_$i;\n" .
               $this->g($i, $tree);
    }

    /** @return string */
    private function gand($i, $tree)
    {
        return "\$_pos$i = \$this->_p;\n" .
               $this->g($i + 1, $tree) .
               "\$_$i = array(\$_" . ($i + 1) . "[0], NULL);\n" .
               "\$this->_p = \$_pos$i;\n";
    }

    /** @return string */
    private function gnot($i, $tree)
    {
        return "\$_pos$i = \$this->_p;\n" .
               $this->g($i + 1, $tree) .
               "\$_$i = array(!\$_" . ($i + 1) . "[0], NULL);\n" .
               "\$this->_p = \$_pos$i;\n";
    }

    /** @return string */
    private function gopt($i, $tree)
    {
        return "\$_$i = array(TRUE, NULL);\n" .
               "\$_pos$i = \$this->_p;\n" .
               $this->g($i + 1, $tree) .
               "if (!\$_" . ($i + 1) . "[0]) {\n" .
               "    \$this->_p = \$_pos$i;\n" .
               "} else {\n" .
               "    \$_$i = \$_" . ($i + 1) . ";\n" .
               "}\n";
    }

    /** @return string */
    private function gmr0($i, $tree)
    {
        return "\$_$i = array(TRUE, array());\n" .
               "do {\n" .
               "    \$_pos$i = \$this->_p;\n" .
                    $this->i($this->g($i + 1, $tree)) .
               "    if (!\$_" . ($i + 1) . "[0]) { \$this->_p = \$_pos$i; }\n" .
               "    else { \$_{$i}[1][] = \$_" . ($i + 1) . "[1]; }\n" .
               "} while (\$_" . ($i + 1) . "[0]);\n" .
               ($this->isSimple($tree) ? "\$_{$i}[1] = implode('', (array) \$_{$i}[1]);\n" : '');
    }

    /** @return string */
    private function gmr1($i, $tree)
    {
        return "\$_$i = array(FALSE, NULL);\n" .
               $this->g($i + 1, $tree) .
               "if (\$_" . ($i + 1) . "[0]) { \$_{$i}[1] = (array) \$_{$i}[1]; \$_{$i}[1][] = \$_" . ($i + 1) . "[1]; }\n" .
               "\$_{$i}[0] = \$_" . ($i + 1) . "[0];\n" .
               "while (\$_" . ($i + 1) . "[0]) {\n" .
               "    \$_pos$i = \$this->_p;\n" .
                    $this->i($this->g($i + 1, $tree)) .
               "    \$_{$i}[1] = (array) \$_{$i}[1];\n" .
               "    if (!\$_" . ($i + 1) . "[0]) { \$this->_p = \$_pos$i; }\n" .
               "    else { \$_{$i}[1][] = \$_" . ($i + 1) . "[1]; };\n" .
               "}\n" .
               ($this->isSimple($tree) ? "\$_{$i}[1] = implode('', (array) \$_{$i}[1]);\n" : '');
    }

    /** @return string */
    private function gapp($i, $name)
    {
        return "\$_$i = \$this->_parse_$name();\n";
    }


    /** @return string */
    private function glit($i, $s)
    {
        $encapsed_s = '"' . $this->formatForString($s) . '"';

        return "\$_$i = array(FALSE, NULL);\n" .
               "if ((\$_{$i}_ = substr(\$this->_s, \$this->_p, " . strlen($s) . ")) === " . $encapsed_s . ") {\n" .
               "    \$_$i = array(TRUE, \$_{$i}_);\n" .
               "    \$this->_p += " . strlen($s) . ";\n" .
               "} else {\n" .
               "    \$this->_failed(" . var_export($encapsed_s, TRUE) . ");\n" .
               "}\n";
    }

    /** @return string */
    private function grng()
    {
        $args = func_get_args();
        $i = array_shift($args);

        $or = array();
        $str = "";

        foreach ($args as $r) {
            if (is_array($r)) {
                $or[] = "(" . $r[0] . " <= \$_{$i}_ && \$_{$i}_ <= " . $r[1] . ")";
                $str .= chr($r[0]) . "-" . chr($r[1]);
            } else {
                $or[] = "(\$_{$i}_ === $r)";
                $str .= chr($r);
            }
        }

        $str = $this->formatForString("[" . $str . "]");

        return "\$_$i = array(FALSE, NULL);\n" .
               "\$_{$i}_ = ord(substr(\$this->_s, \$this->_p, 1));\n" .
               "if (isset(\$this->_s[\$this->_p]) && (" . implode(" || ", $or) . ")) {\n" .
               "    \$_$i = array(TRUE, substr(\$this->_s, \$this->_p, 1));\n" .
               "    \$this->_p += 1;\n" .
               "} else {\n" .
               "    \$this->_failed(" . var_export($str, TRUE) . ");\n" .
               "}\n";
    }

    /** @return string */
    private function formatForString($s)
    {
        $formatted = "";

        foreach (str_split($s) as $c) {
            if ($c === "\\") { $formatted .= "\\\\"; }
            else if (ctype_print($c) || $c === ' ') { $formatted .= $c; }
            else if ($c === "\t") { $formatted .= "\\t"; }
            else if ($c === "\n") { $formatted .= "\\n"; }
            else if ($c === "\r") { $formatted .= "\\r"; }
            else {
                $hex = dechex(ord($c));
                $formatted .= '\x' . (strlen($hex) < 2 ? str_pad($hex, 2, '0', STR_PAD_LEFT) : $hex);
            }
        }

        return $formatted;
    }

    /** @return string */
    private function gany($i)
    {
        return "\$_$i = array(isset(\$this->_s[\$this->_p]), NULL);\n" .
               "if (\$_{$i}[0]) {\n" . 
               "    \$_{$i}[1] = \$this->_s[\$this->_p];\n" .
               "    \$this->_p += 1;\n" .
               "} else {\n" .
               "    \$this->_failed('any character');\n" .
               "}\n";
    }

    /** @return string */
    private function gspr($i, $code)
    {
        return "\$_$i = array((bool) " . $this->gcode($code) . ", NULL);\n";
    }

    /** @return string */
    private function gcode($code)
    {
        $params = array();
        $args = array();

        foreach (array_keys($this->bind) as $varname) {
            $params[] = "&\${$varname}";
            $args[] = "\${$varname}[1]";
        }

        $methodname = "_" . count($this->methods);
        $this->methods[] = "function " . $methodname . "(" . implode (", ", $params) . ") {\n" .
                           $code .
                           "\n}\n";

        return "\$this->$methodname(" . implode(", ", $args) . ")";
    }

    /** @return bool */
    private function isSimple($tree)
    {
        $rest = array_slice($tree, 1);
        $ret = NULL;

        switch ($tree[0]) {
            case 'opt':
            case 'mr0':
            case 'mr1':
                return $this->isSimple($rest[0]);
            break;

            case 'fst':
            case 'all':
                $is = TRUE;
                foreach ($rest as $exp) {
                    if ($is && $this->isSimple($exp)) { continue; }
                    $is = FALSE;
                }
                return $is;
            break;

            case 'app':
            case 'act':
            case 'bnd':
                return FALSE;
            break;

            case 'and':
            case 'spr':
            case 'not':
            case 'lit':
            case 'rng':
            case 'any':
                return TRUE;
            break;

            default:
                die('isSimple: ' . var_export($tree, TRUE));
        }

        return FALSE;
    }

    /** @return bool */
    private function isSafe($tree)
    {
        $rest = array_slice($tree, 1);
        $ret = NULL;

        switch ($tree[0]) {
            case 'opt':
            case 'mr0':
            case 'mr1':
                return $this->isSafe($rest[0]);
            break;

            case 'fst':
            case 'all':
                $is = TRUE;
                foreach ($rest as $exp) {
                    if ($is && $this->isSafe($exp)) { continue; }
                    $is = FALSE;
                    break;
                }
                return $is;
            break;

            case 'spr':
                return FALSE;
            break;

            case 'bnd':
            case 'act':
            case 'app':
            case 'and':
            case 'not':
            case 'lit':
            case 'rng':
            case 'any':
                return TRUE;
            break;

            default:
                die('isSafe: ' . var_export($tree, TRUE));
        }

        return FALSE;
    }

}
