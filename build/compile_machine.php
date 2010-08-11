<?php
class compile_machine
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
        "codes" => array(),
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
        case 'first':
            list($_, $_0) = $node;
            $ret = $this->_0($_0);
        break;
        case 'all':
            list($_, $_0) = $node;
            $ret = $this->_1($_0);
        break;
        case 'action':
            list($_, $_0, $_1) = $node;
            $ret = $this->_2($_0, $_1);
        break;
        case 'bind':
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
        case 'and':
        case 'not':
            list($_, $_0) = $node;
            $ret = $this->_7($_0);
        break;
        case 'optional':
            list($_, $_0) = $node;
            $ret = $this->_8($_0);
        break;
        case 'zero_or_more':
            list($_, $_0) = $node;
            $ret = $this->_9($_0);
        break;
        case 'one_or_more':
            list($_, $_0) = $node;
            $ret = $this->_10($_0);
        break;
        case 'apply':
            list($_, $_0) = $node;
            $ret = $this->_11($_0);
        break;
        case 'literal':
            list($_, $_0) = $node;
            $ret = $this->_12($_0);
        break;
        case 'range':
            list($_, $_0) = $node;
            $ret = $this->_13($_0);
        break;
        case 'any':
            $ret = $this->_14();
        break;
        case 'semantic_predicate':
            list($_, $_0) = $node;
            $ret = $this->_15($_0);
        break;
        case 'position':
            $ret = $this->_16();
        break;
        default:
            $ret = $this->_17();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($node)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        $ret = $this->_walk($node);
            return array($ret, $self->codes);

    }

protected function _0($nodes) { extract($this->_env, EXTR_REFS); foreach ($nodes as $k => $node) {
        $nodes[$k] = $this->_walk($node);
    }

    $ret = array_pop($nodes);

    while ($node = array_pop($nodes)) {
        $ret = array_merge(
            array(
                array("push", array("register", "p"), array("register", "stack")),
            ),
            $node,
            array(
                array("pop", array("register", "stack"), array("register", "a")),
                array("jumpif", array("not", array("register", "fail")), array("offset", count($ret) + 2)),
                array("set", array("register", "a"), array("register", "p")),
            ),
            $ret
        );
    }

    return $ret;

}
protected function _1($nodes) { extract($this->_env, EXTR_REFS); $is_simple = c(new is_simple, array($this->_nodetype(), $nodes));
    $ret = array();

    $first = TRUE;
    $push = FALSE;
    foreach ($nodes as $k => $node) {
        if ($first) {
            $type = $node[0];
            if ($type === "bind") {
                $type = $node[2][0];
            }

            if (!in_array($type, array("and", "not", "semantic_predicate"))) {
                $first = FALSE;
                $push = TRUE;
            }
        }

        $nodes[$k] = $this->_walk($node);

        if ($is_simple) {
            $nodes[$k][] = array("pop", array("register", "stack"), array("register", "a"));
            $nodes[$k][] = array("append", array("register", "value"), array("register", "a"));
            $nodes[$k][] = array("push", array("register", "a"), array("register", "stack"));

        } else if ($push) {
            $push = FALSE;
            $nodes[$k][] = array("pop", array("register", "stack"), NULL);
            $nodes[$k][] = array("push", array("register", "value"), array("register", "stack"));
        }
    }

    $ret = array_merge($ret, array_pop($nodes));

    while ($node = array_pop($nodes)) {
        $ret = array_merge(
            $node,
            array(
                array("jumpif", array("register", "fail"), array("offset", count($ret) + 1)),
            ),
            $ret
        );
    }

    // because nodes are inserted from back, this is the first instruction and it has
    // to be added after nodes
    $ret = array_merge(
        array(
            array("push", array("value", $is_simple ? "" : NULL), array("register", "stack"))
        ),
        $ret
    );

    // last instruction transfers value from %stack to %value register
    $ret[] = array("pop", array("register", "stack"), array("register", "value"));


    return $ret;

}
protected function _2($node, $code) { extract($this->_env, EXTR_REFS); $n = count($self->codes);
    $self->codes[$n] = $code;

    return array_merge(
        array(
            // converts %env to array of references, so one can mutate state
            // FIXME: inlines are bad, m'kay...
            array("set", array("value", array()), array("register", "b")),
            array("inline", "foreach (\$_env as \$_a => \$_) { \$_b[\$_a] =& \$_env[\$_a]; }"),
            array("push", array("register", "b"), array("register", "stack")),
        ),
        $this->_walk($node),
        array(
            array("jumpif", array("register", "fail"), array("offset", 2)),
            array("run", $n, array("register", "env")),
            array("pop", array("register", "stack"), array("register", "env")),
        )
    );

}
protected function _3($varname, $node) { extract($this->_env, EXTR_REFS); return array_merge(
        $this->_walk($node),
        array(
            array("set", array("register", "value"), array("register_index", "env", $varname)),
        )
    );

}
protected function _4($node) { extract($this->_env, EXTR_REFS); return array_merge(
        array(
            array("push", array("register", "env"), array("register", "stack")),
            array("set", array("value", array()), array("register", "env")),
        ),
        $this->_walk($node),
        array(
            array("pop", array("register", "stack"), array("register", "env")),
        )
    );

}
protected function _5($i, $node) { extract($this->_env, EXTR_REFS); return array_merge(
        array(
            array("inline", "\$this->_environment_stack[] = {$i};"),
        ),
        $this->_walk($node),
        array(
            array("inline", "array_pop(\$this->_environment_stack);"),
        )
    );

}
protected function _6($node) { extract($this->_env, EXTR_REFS); return array_merge(
        array(
            array("inline", "\$this->_environment_stack[] = -1;"),
        ),
        $this->_walk($node),
        array(
            array("inline", "array_pop(\$this->_environment_stack);"),
        )
    );

}
protected function _7($node) { extract($this->_env, EXTR_REFS); return array_merge(
        array(
            array("push", array("register", "p"), array("register", "stack")),
        ),
        $this->_walk($node),
        array(
            array("pop", array("register", "stack"), array("register", "p")),
            array("set", array("value", NULL), array("register", "value")),
        ),
        ($this->_nodetype() === "not"
        ? array(
            array("set", array("not", array("register", "fail")), array("register", "fail")),
        )
        : array())
    );

}
protected function _8($node) { extract($this->_env, EXTR_REFS); return array_merge(
        array(
            array("push", array("register", "p"), array("register", "stack")),
        ),
        $this->_walk($node),
        array(
            array("pop", array("register", "stack"), array("register", "a")),
            array("jumpif", array("not", array("register", "fail")), array("offset", 4)),
            array("set", array("value", FALSE), array("register", "fail")),
            array("set", array("value", NULL), array("register", "value")),
            array("set", array("register", "a"), array("register", "p")),
        )
    );

}
protected function _9($node) { extract($this->_env, EXTR_REFS); $is_simple = c(new is_simple, $node);
    $ret = $this->_walk($node);

    return array_merge(
        array(
            array("push", array("register", "p"), array("register", "stack")),
            array("push", array("value", $is_simple ? "" : array()), array("register", "stack")),
        ),
        $ret,
        array(
            array("jumpif", array("register", "fail"), array("offset", 7)),
            array("pop", array("register", "stack"), array("register", "a")),
            array($is_simple ? "append" : "arrayappend", array("register", "value"), array("register", "a")),
            array("pop", array("register", "stack"), NULL),
            array("push", array("register", "p"), array("register", "stack")),
            array("push", array("register", "a"), array("register", "stack")),
            array("jump", array("offset", -(6 + count($ret)))),
            array("pop", array("register", "stack"), array("register", "value")),
            array("pop", array("register", "stack"), array("register", "p")),
            array("set", array("value", FALSE), array("register", "fail")),
        )
    );

}
protected function _10($node) { extract($this->_env, EXTR_REFS); $is_simple = c(new is_simple, $node);
    $ret = $this->_walk($node);

    return array_merge(
        array(
            array("push", array("register", "p"), array("register", "stack")),
            array("push", array("value", $is_simple ? "" : array()), array("register", "stack")),
            array("push", array("value", TRUE), array("register", "stack")),
        ),
        $ret,
        array(
            array("jumpif", array("register", "fail"), array("offset", 9)),
            array("pop", array("register", "stack"), NULL),
            array("pop", array("register", "stack"), array("register", "a")),
            array($is_simple ? "append" : "arrayappend", array("register", "value"), array("register", "a")),
            array("pop", array("register", "stack"), NULL),
            array("push", array("register", "p"), array("register", "stack")),
            array("push", array("register", "a"), array("register", "stack")),
            array("push", array("value", FALSE), array("register", "stack")),
            array("jump", array("offset", -(8 + count($ret)))),
            array("pop", array("register", "stack"), array("register", "a")),
            array("pop", array("register", "stack"), array("register", "value")),
            array("pop", array("register", "stack"), array("register", "p")),
            array("jumpif", array("register", "a"), array("offset", 2)),
            array("set", array("value", FALSE), array("register", "fail")),
        )
    );

}
protected function _11($name) { extract($this->_env, EXTR_REFS); return array(
        array("push", array("offset", 2), array("register", "stack")),
        array("jump", array("label", $name)),
    );

}
protected function _12($s) { extract($this->_env, EXTR_REFS); return array(
        array("literal", $s),
    );

}
protected function _13($match) { extract($this->_env, EXTR_REFS); return array(
        array("range", $match),
    );

}
protected function _14() { extract($this->_env, EXTR_REFS); return array(
        array("any"),
    );

}
protected function _15($code) { extract($this->_env, EXTR_REFS); $n = count($self->codes);
    $self->codes[$n] = $code;

    return array(
        // converts %env to array of references, so one can mutate state
        // FIXME: inlines are bad, m'kay...
        array("inline", "foreach (\$_env as \$_a => \$_) { \$_env[\$_a] =& \$_env[\$_a]; }"),
        array("run", $n, array("register", "env")),
        array("set", array("not", array("register", "value")), array("register", "fail")),
        array("set", array("value", NULL), array("register", "value")),
    );

}
protected function _16() { extract($this->_env, EXTR_REFS); return array(
        array("position"),
    );

}
protected function _17() { extract($this->_env, EXTR_REFS); var_dump($this->_node());
    die("Unexpected node.\n");

}

}
