<?php
class generate_php
{
    private $_stack = array();
    private $_env;
    private $_index;

    public function __construct()
    {
        $this->_init();
    }

    public function walk($node)
    {
        /*$this->_init();*/
        return $this->_walk($node);
    }

    public function walkeach(array $nodes)
    {
        /*$this->_init();*/
        return $this->_walkeach($nodes);
    }

    protected function _init()
    {
        $this->_env = array();
$self = (object) array(
        "i" => 0,
        "bound" => array(),
        "codes" => array(),
        "definitions" => array(),
        "common" => NULL,
    );

        $this->_env = get_defined_vars();
    }

    protected function _node()
    {
        return end($this->_stack);
    }

    protected function _nodetype()
    {
        return current($this->_node());
    }

    protected function _stack()
    {
        return $this->_stack;
    }

    protected function _root()
    {
        reset($this->_stack);
        return current($this->_stack);
    }

    protected function _index()
    {
        return $this->_index;
    }

    protected function _walkeach(array $nodes)
    {
        $ret = array();

        foreach ($nodes as $k => $node) {
            $this->_index = $k;
            $ret[$k] = $this->_walk($node);
        }

        return $ret;
    }

    protected function _walk($node)
    {
        array_push($this->_stack, $node);
        $ret = NULL;

        switch ($node[0]) {
        case 'prolog_':
            $ret = $this->_0();
        break;
        case 'parse_':
            $ret = $this->_1();
        break;
        case 'codes_':
            $ret = $this->_2();
        break;
        case 'rule_':
            list($_, $_0, $_1) = $node;
            $ret = $this->_3($_0, $_1);
        break;
        case 'quarantine':
            list($_, $_0) = $node;
            $ret = $this->_4($_0);
        break;
        case 'environment':
            list($_, $_0, $_1) = $node;
            $ret = $this->_5($_0, $_1);
        break;
        case 'empty_environment':
            list($_, $_0) = $node;
            $ret = $this->_6($_0);
        break;
        case 'first':
            list($_, $_0) = $node;
            $ret = $this->_7($_0);
        break;
        case 'all':
            list($_, $_0) = $node;
            $ret = $this->_8($_0);
        break;
        case 'action':
            list($_, $_0, $_1) = $node;
            $ret = $this->_9($_0, $_1);
        break;
        case 'bind':
            list($_, $_0, $_1) = $node;
            $ret = $this->_10($_0, $_1);
        break;
        case 'and':
        case 'not':
            list($_, $_0) = $node;
            $ret = $this->_11($_0);
        break;
        case 'optional':
            list($_, $_0) = $node;
            $ret = $this->_12($_0);
        break;
        case 'zero_or_more':
        case 'one_or_more':
            list($_, $_0) = $node;
            $ret = $this->_13($_0);
        break;
        case 'apply':
            list($_, $_0) = $node;
            $ret = $this->_14($_0);
        break;
        case 'literal':
            list($_, $_0) = $node;
            $ret = $this->_15($_0);
        break;
        case 'range':
            list($_, $_0) = $node;
            $ret = $this->_16($_0);
        break;
        case 'any':
            $ret = $this->_17();
        break;
        case 'semantic_predicate':
            list($_, $_0) = $node;
            $ret = $this->_18($_0);
        break;
        case 'position':
            $ret = $this->_19();
        break;
        case 'code_':
            list($_, $_0) = $node;
            $ret = $this->_20($_0);
        break;
        case 'failed_':
            list($_, $_0) = $node;
            $ret = $this->_21($_0);
        break;
        default:
            $ret = $this->_22();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($input)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        list($namespace, $name, $inits, $invoke, $self->definitions) = c(new process_input, $input);
            return c($self->common = new generate_common, $this, $namespace, $name, $inits, $invoke);

    }

protected function _0() { extract($this->_env, EXTR_REFS); return "private \$_s;\n" .
           "private \$_p;\n" .
           "private \$_maxp;\n" .
           "private \$_expected;\n" .
           "private \$_memo;\n";

}
protected function _1() { extract($this->_env, EXTR_REFS); $ret = "public function parse(\$s) {\n" .
           "    \$this->_s = \$s;\n" .
           "    \$this->_p = \$this->_maxp = 0;\n" .
           "    \$this->_expected = array();\n" .
           "    \$this->_memo = array();\n" .
           "    list(\$ok, \$result) = \$this->_parse_0();\n" .
           "    if (!\$ok) {\n" .
           "        \$before = str_replace(array(\"\\r\\n\", \"\\r\"), \"\\n\", substr(\$s, 0, \$this->_maxp));\n" .
           "        \$after = str_replace(array(\"\\r\\n\", \"\\r\"), \"\\n\", substr(\$s, \$this->_maxp));\n" .
           "        \$line = 1;\n" .
           "\n" .
           "        if ((\$pos = strrpos(\$before, \"\\n\")) !== FALSE) {\n" .
           "            \$line = substr_count(\$before, \"\\n\") + 1;\n" .
           "            \$before = (string) substr(\$before, \$pos + 1);\n" .
           "        }\n" .
           "\n" .
           "        if ((\$pos = strpos(\$after, \"\\n\")) !== FALSE) {\n" .
           "            \$after = substr(\$after, 0, \$pos);\n" .
           "        }\n" .
           "\n" .
           "        return array(FALSE, NULL, (object) array('position' => \$this->_maxp, 'line' => \$line, 'column' => strlen(\$before) + 1, 'context' => \$before . \$after, 'expected' => \$this->_expected));\n" .
           "    }\n" .
           "\n" .
           "    return array(TRUE, \$result, NULL);\n" .
           "}\n";

    foreach ($self->definitions as $k => $definition) {
        $ret .= $this->_walk(array("rule_", $k, $definition));
    }

    $ret .= $this->_walk(array("codes_"));

    return $ret;

}
protected function _2() { extract($this->_env, EXTR_REFS); $ret = "";

    foreach ($self->codes as $code) {
        $ret .= $code . "\n";
    }

    return $ret;

}
protected function _3($name, $node) { extract($this->_env, EXTR_REFS); $self->i = 0;
    $self->bound = array();

    return "private function _parse_$name() {\n" .
           "    if (isset(\$this->_memo[$name . '@' . \$this->_p])) {\n" .
           "        list(\$_0, \$this->_p) = \$this->_memo[$name . '@' . \$this->_p];\n" .
           "        return \$_0;\n" .
           "    }\n" .
           "    \$_startp = \$this->_p;" .
                i($this->_walk($node)) .
           "    \$this->_memo[$name . '@' . \$_startp] = array(\$_0, \$this->_p);\n" .
           "    return \$_0;\n" .
           "}\n";

}
protected function _4($node) { extract($this->_env, EXTR_REFS); $saved_bound = $self->bound;
    $self->bound = array();

    $ret = $this->_walk($node);

    $self->bound = $saved_bound;

    return $ret;

}
protected function _5($i, $node) { extract($this->_env, EXTR_REFS); return $self->common->walk(array("push_environment_", $i)) .
           $this->_walk($node) .
           $self->common->walk(array("pop_environment_"));

}
protected function _6($node) { extract($this->_env, EXTR_REFS); return $self->common->walk(array("push_environment_", -1)) .
           $this->_walk($node) .
           $self->common->walk(array("pop_environment_"));

}
protected function _7($nodes) { extract($this->_env, EXTR_REFS); $myi = $self->i;
    $self->i++;

    $ret = "\$_{$myi} = array(FALSE, NULL);\n" .
           "\$_pos{$myi} = \$this->_p;\n" .
           "do {\n";

    foreach ($nodes as $node) {
        $ret .= i($this->_walk($node) .
                "if (!\$_{$self->i}[0]) {\n" .
                "    \$this->_p = \$_pos{$myi};\n" .
                "} else {\n" .
                "    \$_{$myi} = \$_{$self->i};\n" .
                "    break;\n" .
                "}\n");
        $self->i++;
    }

    $ret .= "} while(0);\n";

    $self->i = $myi;

    return $ret;

}
protected function _8($nodes) { extract($this->_env, EXTR_REFS); $is_simple = c(new is_simple, array($this->_nodetype(), $nodes));

    $myi  = $self->i;

    foreach ($nodes as $k => $node) {
        if ($node[0] === "bind") {
            $node = $node[2];
        }

        if (in_array($node[0], array("and", "not", "semantic_predicate"))) {
            continue;
        }

        $reti = $myi + $k + 1;
        break;
    }

    if (!(isset($reti) && $reti !== NULL)) {
        $reti = $myi;
    }

    $ret = "";

    if ($is_simple) {
        $ret .= "\$_s{$myi} = '';\n";
    }

    $ret .= "do {\n";

    foreach ($nodes as $k => $node) {
        $self->i = $myi + $k + 1;
        $ret .= i($this->_walk($node) .
                "if (!\$_{$self->i}[0]) {\n" .
                "    \$_{$reti} = array(FALSE, NULL);\n" .
                "    break;\n" .
                "}\n");

        if ($is_simple) {
            $ret .= i("\$_s{$myi} .= \$_{$self->i}[1];\n");
        }
    }

    $ret .= "} while(0);\n" .
            "\$_{$myi} = \$_{$reti};\n" .
            ($is_simple ? "\$_{$myi}[1] = \$_s{$myi};\n" : "");

    $self->i = $myi;

    return $ret;

}
protected function _9($node, $code) { extract($this->_env, EXTR_REFS); $myi = $self->i;
    $self->i = $myi + 1;
    $saved_bound = $self->bound;

    $ret = $this->_walk($node) .
           "\$_{$myi} = array(FALSE, NULL);\n" .
           "if (\$_{$self->i}[0]) {\n" .
           "    \$_{$myi} = array(TRUE, " . $this->_walk(array("code_", $code)) . ");\n" .
           "}\n";

    $self->bound = $saved_bound;
    $self->i = $myi;

    return $ret;

}
protected function _10($varname, $node) { extract($this->_env, EXTR_REFS); $self->bound[$varname] = $self->i;

    return $this->_walk($node);

}
protected function _11($node) { extract($this->_env, EXTR_REFS); $myi = $self->i;
    $self->i = $myi + 1;

    $ret = "\$_pos{$myi} = \$this->_p;\n" .
           $this->_walk($node) .
           "\$_{$myi} = array(" . ($this->_nodetype() === "not" ? "!" : "") . "\$_{$self->i}[0], NULL);\n" .
           "\$this->_p = \$_pos{$myi};\n";

    $self->i = $myi;

    return $ret;

}
protected function _12($node) { extract($this->_env, EXTR_REFS); $myi = $self->i;
    $self->i = $myi + 1;

    $ret = "\$_{$myi} = array(TRUE, NULL);\n" .
           "\$_pos{$myi} = \$this->_p;\n" .
           $this->_walk($node) .
           "if (!\$_{$self->i}[0]) {\n" .
           "    \$this->_p = \$_pos{$myi};\n" .
           "} else {\n" .
           "    \$_{$myi} = \$_{$self->i};\n" .
           "}\n";

    $self->i = $myi;

    return $ret;

}
protected function _13($node) { extract($this->_env, EXTR_REFS); $is_simple = c(new is_simple, $node);

    $myi = $self->i;
    $self->i = $myi + 1;

    $ret = "\$_{$myi} = array(" . ($this->_nodetype() === "zero_or_more" ? "TRUE" : "FALSE") .
                ", " . ($is_simple ? "''" : "array()") . ");\n" .
           "do {\n" .
           "    \$_pos{$myi} = \$this->_p;\n" .
                i($this->_walk($node)) .
           "    if (!\$_{$self->i}[0]) { \$this->_p = \$_pos{$myi}; }\n" .
           "    else {\n" .
           "        \$_{$myi}[0] = TRUE;\n" . ($is_simple ?
           "        \$_{$myi}[1] .= \$_{$self->i}[1];\n" :
           "        \$_{$myi}[1][] = \$_{$self->i}[1];\n") .
           "    }\n" .
           "} while (\$_{$self->i}[0]);\n";

    $self->i = $myi;

    return $ret;

}
protected function _14($name) { extract($this->_env, EXTR_REFS); return "\$_{$self->i} = \$this->_parse_$name();\n";

}
protected function _15($s) { extract($this->_env, EXTR_REFS); $encapsed_s = '"' . $self->common->walk(array("format_", $s)) . '"';

    return "\$_{$self->i} = array(FALSE, NULL);\n" .
           "if ((\$_{$self->i}_ = substr(\$this->_s, \$this->_p, " . strlen($s) . ")) === " . $encapsed_s . ") {\n" .
           "    \$_{$self->i} = array(TRUE, \$_{$self->i}_);\n" .
           "    \$this->_p += " . strlen($s) . ";\n" .
           "} else {\n" .
                i($this->_walk(array("failed_", $encapsed_s))) .
           "}\n";

}
protected function _16($match) { extract($this->_env, EXTR_REFS); $or = array();
    $str = "";

    foreach ($match as $r) {
        if (is_array($r)) {
            $or[] = "(" . $r[0] . " <= \$_{$self->i}_ && \$_{$self->i}_ <= " . $r[1] . ")";
            $str .= chr($r[0]) . "-" . chr($r[1]);
        } else {
            $or[] = "(\$_{$self->i}_ === $r)";
            $str .= chr($r);
        }
    }

    $str = $self->common->walk(array("format_", "[" . $str . "]"));

    return "\$_{$self->i} = array(FALSE, NULL);\n" .
           "if (isset(\$this->_s[\$this->_p]) && " .
               "is_int(\$_{$self->i}_ = ord(\$this->_s[\$this->_p])) && " .
               "(" . implode(" || ", $or) . ")) {\n" .
           "    \$_{$self->i} = array(TRUE, \$this->_s[\$this->_p]);\n" .
           "    \$this->_p++;\n" .
           "} else {\n" .
                i($this->_walk(array("failed_", $str))) .
           "}\n";

}
protected function _17() { extract($this->_env, EXTR_REFS); return "\$_{$self->i} = array(FALSE, NULL);\n" .
           "if (isset(\$this->_s[\$this->_p])) {\n" .
           "    \$_{$self->i} = array(TRUE, \$this->_s[\$this->_p]);\n" .
           "    \$this->_p++;\n" .
           "} else {\n" .
                i($this->_walk(array("failed_", "any character"))) .
           "}\n";

}
protected function _18($code) { extract($this->_env, EXTR_REFS); return "\$_{$self->i} = array((bool) " . $this->_walk(array("code_", $code)) . ", NULL);\n";

}
protected function _19() { extract($this->_env, EXTR_REFS); return "\$_before{$self->i} = str_replace(array(\"\\r\\n\", \"\\r\"), \"\\n\", (string) substr(\$this->_s, 0, \$this->_p));\n" .
           "\$_line{$self->i} = 1;\n" .
           "if ((\$_pos{$self->i} = strrpos(\$_before{$self->i}, \"\\n\")) !== FALSE) {\n" .
           "    \$_line{$self->i} = substr_count(\$_before{$self->i}, \"\\n\") + 1;\n" .
           "    \$_before{$self->i} = (string) substr(\$_before{$self->i}, \$_pos{$self->i} + 1);\n" .
           "}\n" .
           "\$_{$self->i} = array(TRUE, array(\$_line{$self->i}, strlen(\$_before{$self->i}) + 1));\n";

}
protected function _20($code) { extract($this->_env, EXTR_REFS); $input = array();

    foreach ($self->bound as $varname => $i) {
        $input[] = "'{$varname}' => &\$_{$i}[1]";
    }

    $name = "_" . count($self->codes);
    $self->codes[] = "private function " . $name . "() {\n" .
                          i($self->common->walk(array("extract_environment_"))) .
                     "    extract(func_get_arg(0), EXTR_OVERWRITE | EXTR_REFS);\n" .
                          i($code) .
                     "\n}\n";

    return "\$this->$name(array(" . implode(", ", $input) . "))";

}
protected function _21($on) { extract($this->_env, EXTR_REFS); return "if (\$this->_p >= \$this->_maxp) {\n" .
           "    if (\$this->_p > \$this->_maxp) {\n" .
           "        \$this->_maxp = \$this->_p;\n" .
           "        \$this->_expected = array();\n" .
           "    }\n" .
           "    if (!in_array(" . var_export($on, TRUE) . ", \$this->_expected)) {\n" .
           "        \$this->_expected[] = " . var_export($on, TRUE) . ";\n" .
           "    }\n" .
           "}\n";

}
protected function _22() { extract($this->_env, EXTR_REFS); var_dump($this->_node());
    die("Unexpected node.\n");

}

}
