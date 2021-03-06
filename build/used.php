<?php
class used
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
        case 'rule':
            list($_, $_0, $_1) = $node;
            $ret = $this->_0($_0, $_1);
        break;
        case 'first':
        case 'all':
            list($_, $_0) = $node;
            $ret = $this->_1($_0);
        break;
        case 'environment':
            list($_, $_0, $_1) = $node;
            $ret = $this->_2($_0, $_1);
        break;
        case 'optional':
        case 'zero_or_more':
        case 'one_or_more':
        case 'quarantine':
        case 'empty_environment':
            list($_, $_0) = $node;
            $ret = $this->_3($_0);
        break;
        case 'action':
            list($_, $_0, $_1) = $node;
            $ret = $this->_4($_0, $_1);
        break;
        case 'bind':
            list($_, $_0, $_1) = $node;
            $ret = $this->_5($_0, $_1);
        break;
        case 'apply':
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
            $used = array(key($definitions) => 0);
        
            foreach ($definitions as $definition) {
                $used = array_merge($used, array_flip($this->_walk($definition)));
            }
        
            return array_keys($used);

    }

protected function _0($name, $node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _1($nodes) { extract($this->_env, EXTR_REFS); $used = array();

    foreach ($nodes as $node) {
        $used = array_merge($used, array_flip($this->_walk($node)));
    }

    return array_keys($used);

}
protected function _2($i, $node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _3($node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _4($node, $code) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _5($varname, $node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _6($name) { extract($this->_env, EXTR_REFS); return array($name);
}
protected function _7() { extract($this->_env, EXTR_REFS); return array();
}

}
