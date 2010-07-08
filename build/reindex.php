<?php
class reindex
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
        "reindex" => array(),
        "exclusions" => array(),
        "doesnt_exist" => array(),
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
        case 'macro':
            list($_, $_0, $_1, $_2) = $node;
            $ret = $this->_0($_0, $_1, $_2);
        break;
        case 'rule':
            list($_, $_0, $_1) = $node;
            $ret = $this->_1($_0, $_1);
        break;
        case 'first':
        case 'all':
            list($_, $_0) = $node;
            $ret = $this->_2($_0);
        break;
        case 'action':
            list($_, $_0, $_1) = $node;
            $ret = $this->_3($_0, $_1);
        break;
        case 'environment':
            list($_, $_0, $_1) = $node;
            $ret = $this->_4($_0, $_1);
        break;
        case 'and':
        case 'not':
        case 'optional':
        case 'zero_or_more':
        case 'one_or_more':
        case 'quarantine':
        case 'empty_environment':
            list($_, $_0) = $node;
            $ret = $this->_5($_0);
        break;
        case 'bind':
            list($_, $_0, $_1) = $node;
            $ret = $this->_6($_0, $_1);
        break;
        case 'apply':
            list($_, $_0) = $node;
            $ret = $this->_7($_0);
        break;
        case 'expand':
            list($_, $_0, $_1) = $node;
            $ret = $this->_8($_0, $_1);
        break;
        case 'name_':
            list($_, $_0) = $node;
            $ret = $this->_9($_0);
        break;
        default:
            $ret = $this->_10();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($definitions, $reindex)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        $self->reindex = $reindex;
            $ret = $this->_walkeach($definitions);
            return array(array_keys($self->doesnt_exist), $ret);

    }

protected function _0($name, $arguments, $node) { extract($this->_env, EXTR_REFS); $self->exclusions = $arguments;
    $ret = array($this->_nodetype(), $name, $arguments, $this->_walk($node));
    $self->exclusions = array();
    return $ret;

}
protected function _1($name, $node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $name, $this->_walk($node));;
}
protected function _2($nodes) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walkeach($nodes));;
}
protected function _3($node, $code) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($node), $code);
}
protected function _4($i, $node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $i, $this->_walk($node));
}
protected function _5($node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($node));
}
protected function _6($varname, $node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $varname, $this->_walk($node));
}
protected function _7($name) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk(array("name_", $name)));
}
protected function _8($name, $arguments) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk(array("name_", $name)), $this->_walkeach($arguments));
}
protected function _9($name) { extract($this->_env, EXTR_REFS); if (in_array($name, $self->exclusions)) {
        return $name;
    }

    $key = implode(".", (array) $name);

    if (isset($self->reindex[$key])) {
        $name = $self->reindex[$key];
    } else {
        $self->doesnt_exist[$key] = TRUE;
    }

    return $name;

}
protected function _10() { extract($this->_env, EXTR_REFS); return $this->_node();
}

}
