<?php
class check_left_recursive
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
        "leftmost" => array(),
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
        case 'first':
            list($_, $_0) = $node;
            $ret = $this->_1($_0);
        break;
        case 'all':
            list($_, $_0) = $node;
            $ret = $this->_2($_0);
        break;
        case 'action':
            list($_, $_0, $_1) = $node;
            $ret = $this->_3($_0, $_1);
        break;
        case 'optional':
        case 'zero_or_more':
        case 'one_or_more':
            list($_, $_0) = $node;
            $ret = $this->_4($_0);
        break;
        case 'bind':
            list($_, $_0, $_1) = $node;
            $ret = $this->_5($_0, $_1);
        break;
        case 'expand':
            list($_, $_0, $_1) = $node;
            $ret = $this->_6($_0, $_1);
        break;
        case 'apply':
            list($_, $_0) = $node;
            $ret = $this->_7($_0);
        break;
        default:
            $ret = $this->_8();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($file, $rules)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        $this->_walkeach($rules);
        
            $left_recursive = array();
        
            foreach ($self->leftmost as $name => $leftmost) {
                $chains = array();
                foreach ($leftmost as $l) {
                    $chains[] = array($l);
                }
        
                $newchains = array();
        
                do {
                    $done = TRUE;
                    foreach ($chains as $chain) {
                        if (!isset($self->leftmost[end($chain)])) { continue; }
                        foreach ($self->leftmost[end($chain)] as $l) {
                            if (in_array($l, $chain)) {
                                if ($l === $chain[0]) {
                                    $left_recursive[] = array_merge($chain, array($l));
                                }
                            } else {
                                $newchains[] = array_merge($chain, array($l));
                                $done = FALSE;
                            }
                        }
                    }
        
                    $chains = $newchains;
                    $newchains = array();
                } while (!$done);
            }
        
            if (!empty($left_recursive)) {
                foreach ($left_recursive as $i => $l) {
                    $left_recursive[$i] = implode(" -> ", $l);
                }
        
                die("Left recursive rules in file {$file}:\n" .
                    "  " . implode("\n  ", $left_recursive) . "\n");
            }
        
            return TRUE;

    }

protected function _0($name, $node) { extract($this->_env, EXTR_REFS); $self->leftmost[$name] = array_flip(array_flip($this->_walk($node)));

}
protected function _1($nodes) { extract($this->_env, EXTR_REFS); return call_user_func_array("array_merge", $this->_walkeach($nodes));
}
protected function _2($nodes) { extract($this->_env, EXTR_REFS); return $this->_walk($nodes[0]);
}
protected function _3($node, $code) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _4($node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _5($varname, $node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _6($name, $arguments) { extract($this->_env, EXTR_REFS); return call_user_func_array("array_merge", $this->_walkeach($arguments));
}
protected function _7($name) { extract($this->_env, EXTR_REFS); return is_string($name) ? array($name) : array();
}
protected function _8() { extract($this->_env, EXTR_REFS); return array();
}

}
