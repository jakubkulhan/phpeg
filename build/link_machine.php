<?php
class link_machine
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
        "labels" => array(),
        "returns" => array(),
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
        case 'stackinit':
            list($_, $_0) = $node;
            $ret = $this->_0($_0);
        break;
        case 'set':
        case 'push':
        case 'pop':
        case 'append':
        case 'arrayappend':
            list($_, $_0, $_1) = $node;
            $ret = $this->_1($_0, $_1);
        break;
        case 'jump':
            list($_, $_0) = $node;
            $ret = $this->_2($_0);
        break;
        case 'jumpif':
            list($_, $_0, $_1) = $node;
            $ret = $this->_3($_0, $_1);
        break;
        case 'run':
            list($_, $_0, $_1) = $node;
            $ret = $this->_4($_0, $_1);
        break;
        case 'offset':
            list($_, $_0) = $node;
            $ret = $this->_5($_0);
        break;
        case 'label':
            list($_, $_0) = $node;
            $ret = $this->_6($_0);
        break;
        default:
            $ret = $this->_7();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($definitions)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        reset($definitions);
        
            $ret = array(
                array("stackinit", array("register", "stack")),
                array("set", array("value", FALSE), array("register", "fail")),
                array("set", array("value", NULL), array("register", "value")),
                array("set", array("value", NULL), array("register", "a")),
                array("set", array("value", NULL), array("register", "b")),
                array("set", array("value", NULL), array("register", "c")),
                array("set", array("value", NULL), array("register", "d")),
                array("set", array("value", array()), array("register", "env")),
                array("push", array("offset", 2), array("register", "stack")),
                array("jump", array("label", key($definitions))),
                array("end"),
            );
        
            $codes = array();
            $code_to_label = array();
        
            foreach ($definitions as $label => $definition) {
                $self->labels[$label] = count($ret);
                $self->returns[$label] = array();
        
                list($instructions, $newcodes) = c(new compile_machine, $definition);
        
                $instructions = array_merge(
                    array(
                        array("push", array("register", "env"), array("register", "stack")),
                        array("set", array("value", array()), array("register", "env")),
                    ),
                    $instructions,
                    array(
                        array("pop", array("register", "stack"), array("register", "env")),
                    )
                );
        
                $startn = count($codes);
                $codes = array_merge($codes, $newcodes);
                for ($i = $startn, $n = $startn + count($newcodes); $i < $n; ++$i) {
                    $code_to_label[$i] = $label;
                }
        
                foreach ($instructions as &$instruction) {
                    if ($instruction[0] === "run") {
                        $instruction[1] += $startn;
                    }
                }
        
                $instructions[] = array("pop", array("register", "stack"), array("register", "a"));
                $instructions[] = array("return", $label, array("register", "a"));
                $ret = array_merge($ret, $instructions);
            }
        
            $ret = $this->_walkeach($ret);
        
            return array($ret, $codes, $code_to_label, $self->returns);

    }

protected function _0($reg) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $reg);
}
protected function _1($src, $dst) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($src), $this->_walk($dst));
}
protected function _2($addr) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($addr));
}
protected function _3($cond, $addr) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($cond), $this->_walk($addr));
}
protected function _4($n, $env) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $n, $this->_walk($env));
}
protected function _5($offset) { extract($this->_env, EXTR_REFS); return array("value", $this->_index() + $offset);
}
protected function _6($label) { extract($this->_env, EXTR_REFS); $self->returns[$label][] = $this->_index() + 1;
    return array("value", $self->labels[$label]);

}
protected function _7() { extract($this->_env, EXTR_REFS); return $this->_node();
}

}
