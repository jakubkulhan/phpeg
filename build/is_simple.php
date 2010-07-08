<?php
class is_simple
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
        case 'first':
        case 'all':
            list($_, $_0) = $node;
            $ret = $this->_0($_0);
        break;
        case 'environment':
            list($_, $_0, $_1) = $node;
            $ret = $this->_1($_0, $_1);
        break;
        case 'optional':
        case 'zero_or_more':
        case 'one_or_more':
        case 'quarantine':
        case 'empty_environment':
            list($_, $_0) = $node;
            $ret = $this->_2($_0);
        break;
        case 'apply':
        case 'action':
        case 'bind':
            $ret = $this->_3();
        break;
        case 'and':
        case 'semantic_predicate':
        case 'not':
        case 'literal':
        case 'range':
        case 'any':
        case 'position':
            $ret = $this->_4();
        break;
        default:
            $ret = $this->_5();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($node)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        return $this->_walk($node);

    }

protected function _0($nodes) { extract($this->_env, EXTR_REFS); foreach ($this->_walkeach($nodes) as $node) {
        if (!$node) {
            return FALSE;
        }
    }

    return TRUE;

}
protected function _1($i, $node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _2($node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _3() { extract($this->_env, EXTR_REFS); return FALSE;
}
protected function _4() { extract($this->_env, EXTR_REFS); return TRUE;
}
protected function _5() { extract($this->_env, EXTR_REFS); return FALSE;
}

}
