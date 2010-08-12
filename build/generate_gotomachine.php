<?php
class generate_gotomachine
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
        "program" => array(),
        "codes" => array(),
        "code_to_label" => array(),
        "returns" => array(),
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
        case 'phpize_':
            list($_, $_0) = $node;
            $ret = $this->_2($_0);
        break;
        case 'set':
            list($_, $_0, $_1) = $node;
            $ret = $this->_3($_0, $_1);
        break;
        case 'refarray':
            list($_, $_0, $_1) = $node;
            $ret = $this->_4($_0, $_1);
        break;
        case 'append':
            list($_, $_0, $_1) = $node;
            $ret = $this->_5($_0, $_1);
        break;
        case 'arrayappend':
            list($_, $_0, $_1) = $node;
            $ret = $this->_6($_0, $_1);
        break;
        case 'stackinit':
            list($_, $_0) = $node;
            $ret = $this->_7($_0);
        break;
        case 'push':
            list($_, $_0, $_1) = $node;
            $ret = $this->_8($_0, $_1);
        break;
        case 'pop':
            list($_, $_0, $_1) = $node;
            $ret = $this->_9($_0, $_1);
        break;
        case 'jump':
            list($_, $_0) = $node;
            $ret = $this->_10($_0);
        break;
        case 'jumpif':
            list($_, $_0, $_1) = $node;
            $ret = $this->_11($_0, $_1);
        break;
        case 'return':
            list($_, $_0, $_1) = $node;
            $ret = $this->_12($_0, $_1);
        break;
        case 'run':
            list($_, $_0, $_1) = $node;
            $ret = $this->_13($_0, $_1);
        break;
        case 'value':
            list($_, $_0) = $node;
            $ret = $this->_14($_0);
        break;
        case 'not':
            list($_, $_0) = $node;
            $ret = $this->_15($_0);
        break;
        case 'register':
            list($_, $_0) = $node;
            $ret = $this->_16($_0);
        break;
        case 'register_index':
            list($_, $_0, $_1) = $node;
            $ret = $this->_17($_0, $_1);
        break;
        case 'inline':
            list($_, $_0) = $node;
            $ret = $this->_18($_0);
        break;
        case 'position':
            $ret = $this->_19();
        break;
        case 'any':
            $ret = $this->_20();
        break;
        case 'literal':
            list($_, $_0) = $node;
            $ret = $this->_21($_0);
        break;
        case 'range':
            list($_, $_0) = $node;
            $ret = $this->_22($_0);
        break;
        case 'end':
            $ret = $this->_23();
        break;
        case 'failed_':
            list($_, $_0) = $node;
            $ret = $this->_24($_0);
        break;
        default:
            $ret = $this->_25();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($input)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        list($namespace, $name, $inits, $invoke, $definitions) = c(new process_input, $input);
            list($self->program, $self->codes, $self->code_to_label, $self->returns) = c(new link_machine, $definitions);
            return c($self->common = new generate_common, $this, $namespace, $name, $inits, $invoke);

    }

protected function _0() { extract($this->_env, EXTR_REFS); return "";
}
protected function _1() { extract($this->_env, EXTR_REFS); $ret = "public function parse(\$_s) {\n" .
           "    \$_addr = 0;\n" .
           "    \$_maxp = \$_p = 0;\n" .
           "    \$_expected = array();\n";

    foreach ($self->program as $addr => $instruction) {
        $ret .= "    L$addr: " . $this->_walk($instruction) . "\n";
    }

    $ret .= "    Lend:\n" .
            "    list(\$ok, \$result) = array(!\$_fail, \$_value);\n" .
            "    if (!\$ok) {\n" .
            "        \$before = str_replace(array(\"\\r\\n\", \"\\r\"), \"\\n\", substr(\$_s, 0, \$_maxp));\n" .
            "        \$after = str_replace(array(\"\\r\\n\", \"\\r\"), \"\\n\", substr(\$_s, \$_maxp));\n" .
            "        \$line = 1;\n" .
            "        if ((\$pos = strrpos(\$before, \"\\n\")) !== FALSE) {\n" .
            "            \$line = substr_count(\$before, \"\\n\") + 1;\n" .
            "            \$before = (string) substr(\$before, \$pos + 1);\n" .
            "        }\n" .
            "        if ((\$pos = strpos(\$after, \"\\n\")) !== FALSE) {\n" .
            "            \$after = substr(\$after, 0, \$pos);\n" .
            "        }\n" .
            "        return array(FALSE, NULL, (object) array('position' => \$_maxp, 'line' => \$line, 'column' => strlen(\$before) + 1, 'context' => \$before . \$after, 'expected' => \$_expected));\n" .
            "    }\n" .
            "    return array(TRUE, \$result, NULL);\n" .
            "}\n";

    foreach ($self->codes as $k => $code) {
        $ret .= "private function _$k() {\n" .
                     i($self->common->walk(array("extract_environment_"))) .
                "    extract(func_get_arg(0), EXTR_OVERWRITE | EXTR_REFS);\n" .
                     i($code) .
                "\n}\n";
    }

    return $ret;

}
protected function _2($a) { extract($this->_env, EXTR_REFS); if (is_array($a)) {
        $ret = array();

        foreach ($a as $k => $v) {
            $ret[] = $this->_walk(array("phpize_", $k)) . " => " . $this->_walk(array("phpize_", $v));
        }

        return "array(" . implode(", ", $ret) . ")";

    } else if (is_string($a)) {
        return '"' . $self->common->walk(array("format_", $a)) . '"';

    } else if (is_null($a)) {
        return "NULL";

    } else {
        return var_export($a, TRUE);
    }

}
protected function _3($src, $dst) { extract($this->_env, EXTR_REFS); return $this->_walk($dst) . " = " . $this->_walk($src) . ";";
}
protected function _4($src, $dst) { extract($this->_env, EXTR_REFS); return ($this->_walk($src) !== $this->_walk($dst) ? $this->_walk($dst) . " = array(); " : "") .
           "foreach (" . $this->_walk($src) . " as \$_ref => \$_) { " .
               $this->_walk($dst) . "[\$_ref] =& " . $this->_walk($src) . "[\$_ref]; " .
           "}";

}
protected function _5($src, $dst) { extract($this->_env, EXTR_REFS); return $this->_walk($dst) . " .= " . $this->_walk($src) . ";";
}
protected function _6($src, $dst) { extract($this->_env, EXTR_REFS); return $this->_walk($dst) . "[] = " . $this->_walk($src) . ";";
}
protected function _7($reg) { extract($this->_env, EXTR_REFS); return $this->_walk($reg) . "_sp = -1; " . $this->_walk($reg) . " = array();";
}
protected function _8($src, $dst) { extract($this->_env, EXTR_REFS); return "++" . $this->_walk($dst) . "_sp; " . $this->_walk($dst) . "[" . $this->_walk($dst) . "_sp] = " . $this->_walk($src) . ";";
}
protected function _9($src, $dst) { extract($this->_env, EXTR_REFS); return ($dst !== NULL ? $this->_walk($dst) . " = " . $this->_walk($src) . "[" . $this->_walk($src) . "_sp]; " : "") .
    "--" . $this->_walk($src) . "_sp;";

}
protected function _10($addr) { extract($this->_env, EXTR_REFS); $addr = $this->_walk($addr);

    if ($addr[0] !== "$") {
        return "goto L$addr;";

    } else {
        return "\$_addr = $addr; goto Lgoto;";
    }

}
protected function _11($cond, $addr) { extract($this->_env, EXTR_REFS); return "if (" . $this->_walk($cond) . ") { " . $this->_walk(array("jump", $addr)) . " }";
}
protected function _12($label, $addr) { extract($this->_env, EXTR_REFS); $ret = "return array(FALSE, NULL, NULL);";
    $addr = $this->_walk($addr);
    foreach ($self->returns[$label] as $to) {
        $ret = "if ($addr === $to) { goto L$to; } else { $ret }";
    }
    return $ret;

}
protected function _13($n, $env) { extract($this->_env, EXTR_REFS); return "\$_value = \$this->_$n(" . $this->_walk($env) . ");";
}
protected function _14($v) { extract($this->_env, EXTR_REFS); return $this->_walk(array("phpize_", $v));
}
protected function _15($v) { extract($this->_env, EXTR_REFS); return "!" . $this->_walk($v);
}
protected function _16($r) { extract($this->_env, EXTR_REFS); return "\$_{$r}";
}
protected function _17($r, $i) { extract($this->_env, EXTR_REFS); return "\$_{$r}[" . $this->_walk(array("phpize_", $i)) . "]";
}
protected function _18($code) { extract($this->_env, EXTR_REFS); return $code;
}
protected function _19() { extract($this->_env, EXTR_REFS); return "\$_a = str_replace(array(\"\\r\\n\", \"\\r\"), \"\\n\", (string) substr(\$_s, 0, \$_p)); " .
           "\$_b = 1; " .
           "if ((\$_c = strrpos(\$_a, \"\\n\")) !== FALSE) { " .
               "\$_b = substr_count(\$_a, \"\\n\") + 1; " .
               "\$_a = (string) substr(\$_a, \$_c + 1); " .
           "} " .
           "\$_fail = FALSE; " .
           "\$_value = array(\$_b, strlen(\$_a) + 1);";

}
protected function _20() { extract($this->_env, EXTR_REFS); return "\$_fail = TRUE; " .
           "if (isset(\$_s[\$_p])) { " .
               "\$_fail = FALSE; " .
               "\$_value = \$_s[\$_p]; " .
               "\$_p++; " .
           "} else { " .
               $this->_walk(array("failed_", "any character")) .
           "}";

}
protected function _21($s) { extract($this->_env, EXTR_REFS); $encapsed_s = '"' . $self->common->walk(array("format_", $s)) . '"';

    return "\$_fail = TRUE; " .
           "if ((\$_a = substr(\$_s, \$_p, " . strlen($s) . ")) === " . $encapsed_s . ") { " .
               "\$_fail = FALSE; " .
               "\$_value = \$_a; " .
               "\$_p += " . strlen($s) . "; " .
           "} else { " .
               $this->_walk(array("failed_", $encapsed_s)) .
           "}";

}
protected function _22($match) { extract($this->_env, EXTR_REFS); $or = array();
    $str = "";

    foreach ($match as $r) {
        if (is_array($r)) {
            $or[] = "(" . $r[0] . " <= \$_a && \$_a <= " . $r[1] . ")";
            $str .= chr($r[0]) . "-" . chr($r[1]);
        } else {
            $or[] = "(\$_a === $r)";
            $str .= chr($r);
        }
    }

    $str = $self->common->walk(array("format_", "[" . $str . "]"));

    return "\$_fail = TRUE; " .
           "if (isset(\$_s[\$_p]) && " .
                   "is_int(\$_a = ord(\$_s[\$_p])) && " .
                   "(" . implode(" || ", $or) . ")) { " .
               "\$_fail = FALSE; " .
               "\$_value = \$_s[\$_p]; " .
               "\$_p++; " .
           "} else { " .
                $this->_walk(array("failed_", $str)) .
           "}";

}
protected function _23() { extract($this->_env, EXTR_REFS); return "goto Lend;";
}
protected function _24($on) { extract($this->_env, EXTR_REFS); return "if (\$_p >= \$_maxp) { " .
               "if (\$_p > \$_maxp) { " .
                   "\$_maxp = \$_p; " .
                   "\$_expected = array(); " .
               "} " .
               "if (!in_array(" . var_export($on, TRUE) . ", \$_expected)) { " .
                   "\$_expected[] = " . var_export($on, TRUE) . "; " .
               "} " .
           "} ";

}
protected function _25() { extract($this->_env, EXTR_REFS); var_dump($this->_node());
    die("Unexpected node.\n");

}

}
