<?php
class check_macros_recursive
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
        "current" => NULL,
        "recursion" => array(),
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
        case 'all':
        case 'first':
            list($_, $_0) = $node;
            $ret = $this->_1($_0);
        break;
        case 'action':
            list($_, $_0, $_1) = $node;
            $ret = $this->_2($_0, $_1);
        break;
        case 'and':
        case 'not':
        case 'optional':
        case 'zero_or_more':
        case 'one_or_more':
            list($_, $_0) = $node;
            $ret = $this->_3($_0);
        break;
        case 'bind':
            list($_, $_0, $_1) = $node;
            $ret = $this->_4($_0, $_1);
        break;
        case 'expand':
            list($_, $_0, $_1) = $node;
            $ret = $this->_5($_0, $_1);
        break;
        default:
            $ret = $this->_6();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($file, $macros)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        $this->_walkeach($macros);
        
            $recursive = array();
        
            foreach ($self->recursion as $name => $recursion) {
                $chains = array();
                foreach ($recursion as $r) {
                    $chains[] = array($r);
                }
        
                $newchains = array();
        
                do {
                    $done = TRUE;
                    foreach ($chains as $chain) {
                        if (!isset($self->recursion[end($chain)])) { continue; }
                        foreach ($self->recursion[end($chain)] as $r) {
                            if (in_array($r, $chain)) {
                                if ($r === $chain[0]) {
                                    $recursive[] = array_merge($chain, array($r));
                                }
                            } else {
                                $newchains[] = array_merge($chain, array($r));
                                $done = FALSE;
                            }
                        }
                    }
        
                    $chains = $newchains;
                    $newchains = array();
                } while (!$done);
            }
        
            if (!empty($recursive)) {
                foreach ($recursive as $i => $r) {
                    $recursive[$i] = implode(" -> ", $r);
                }
        
                die("Recursive macros in file {$file}:\n" .
                    "  " . implode("\n  ", $recursive) . "\n");
            }
        
            return TRUE;

    }

protected function _0($name, $arguments, $node) { extract($this->_env, EXTR_REFS); $self->current = $name;
    $self->recursion[$self->current] = array();
    $this->_walk($node);
    $self->recursion[$self->current] =
        array_flip(array_flip($self->recursion[$self->current]));

}
protected function _1($nodes) { extract($this->_env, EXTR_REFS); return $this->_walkeach($nodes);
}
protected function _2($node, $code) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _3($node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _4($varname, $node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _5($name, $arguments) { extract($this->_env, EXTR_REFS); $self->recursion[$self->current][] = $name;
    $this->_walkeach($arguments);

}
protected function _6() { extract($this->_env, EXTR_REFS); return NULL;
}

}
