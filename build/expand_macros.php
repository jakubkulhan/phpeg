<?php
class expand_macros
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
        "macros" => array(),
        "replaces" => array(),
        "environments" => array(),
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
        case 'rule':
            list($_, $_0, $_1) = $node;
            $ret = $this->_0($_0, $_1);
        break;
        case 'environment_':
            list($_, $_0, $_1) = $node;
            $ret = $this->_1($_0, $_1);
        break;
        case 'all':
        case 'first':
            list($_, $_0) = $node;
            $ret = $this->_2($_0);
        break;
        case 'action':
            list($_, $_0, $_1) = $node;
            $ret = $this->_3($_0, $_1);
        break;
        case 'and':
        case 'not':
        case 'optional':
        case 'zero_or_more':
        case 'one_or_more':
        case 'quarantine':
            list($_, $_0) = $node;
            $ret = $this->_4($_0);
        break;
        case 'bind':
            list($_, $_0, $_1) = $node;
            $ret = $this->_5($_0, $_1);
        break;
        case 'apply':
            list($_, $_0) = $node;
            $ret = $this->_6($_0);
        break;
        case 'expand':
            list($_, $_0, $_1) = $node;
            $ret = $this->_7($_0, $_1);
        break;
        default:
            $ret = $this->_8();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($provided, $definitions)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        foreach ($provided as $file => $about) {
                if (!$about->init) {
                    continue;
                }
        
                foreach ($about->reindex as $k => $r) {
                    $self->environments[$r] = $file;
                }
            }
        
            $self->macros = c(new select_nodes, array("macro"), $definitions);
        
            return $this->_walkeach(c(new select_nodes, array("rule"), $definitions));

    }

protected function _0($name, $node) { extract($this->_env, EXTR_REFS); return $this->_walk(array("environment_", $this->_index(), $node));
}
protected function _1($index, $node) { extract($this->_env, EXTR_REFS); if (isset($self->environments[$index])) {
        return array("environment", $self->environments[$index], $this->_walk($node));
    } else {
        return array("empty_environment", $this->_walk($node));
    }

}
protected function _2($nodes) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walkeach($nodes));
}
protected function _3($node, $code) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($node), $code);
}
protected function _4($node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($node));
}
protected function _5($varname, $node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $varname, $this->_walk($node));
}
protected function _6($name) { extract($this->_env, EXTR_REFS); if (($replaces = end($self->replaces)) && isset($replaces[$name])) {
        return $replaces[$name];

    } else {
        return array($this->_nodetype(), $name);
    }

}
protected function _7($name, $arguments) { extract($this->_env, EXTR_REFS); $parameters = $self->macros[$name][2];

    if (count($arguments) !== count($parameters)) {
        die("Incorrect number of arguments for macro {$self->macros[$name][1]}.\n");
    }

    $realarguments = array();

    foreach ($arguments as $argument) {
        $realarguments[] =
            array("quarantine", $this->_walk(array("environment_", $this->_index(), $argument)));
    }

    if (empty($parameters)) {
        $self->replaces[] = array();
    } else {
        $self->replaces[] = array_combine($parameters, $realarguments);
    }

    $ret = $this->_walk($self->macros[$name][3]);

    array_pop($self->replaces);

    return array("quarantine", $this->_walk(array("environment_", $name, $ret)));

}
protected function _8() { extract($this->_env, EXTR_REFS); return $this->_node();
}

}
