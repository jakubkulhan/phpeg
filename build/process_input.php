<?php
class process_input
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
        "file_to_init" => array(),
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
            list($_, $_0) = $node;
            $ret = $this->_0($_0);
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
        case 'quarantine':
        case 'empty_environment':
            list($_, $_0) = $node;
            $ret = $this->_3($_0);
        break;
        case 'bind':
            list($_, $_0, $_1) = $node;
            $ret = $this->_4($_0, $_1);
        break;
        case 'environment':
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

    public function __invoke($input)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        list($provides, $definitions) = c(new parse_input, $input, array(), array());
        
            $definitions = c(new expand_macros, $provides, $definitions);
            c(new check_left_recursive, $provides, $definitions);
        
            foreach ($provides as $file => $about) {
                $rules = array();
                for ($i = 0; $i < $about->count; ++$i) {
                    if (isset($definitions[$about->start + $i])) {
                        $rules[$about->start + $i] = $definitions[$about->start + $i];
                    }
                }
        
        
            }
        
            $used = array_flip(c(new used, $definitions));
        
            $reindex = array();
            $newdefinitions = array();
            foreach ($definitions as $k => $definition) {
                if (isset($used[$k])) {
                    $newdefinitions[] = $definition;
                    $reindex[] = $k;
                }
            }
            $reindex = array_flip($reindex);
        
            list($_, $definitions) = c(new reindex, $newdefinitions, $reindex);
        
            $first = TRUE;
            $namespace = NULL;
            $name = NULL;
            $inits = array();
            $invoke = NULL;
            $constructor = NULL;
            $privates = NULL;
        
            foreach ($provides as $file => $about) {
                if ($about->init) {
                    $self->file_to_init[$file] = count($inits);
                    $inits[count($inits)] = $about->init;
                }
        
                foreach ($about->reindex as $l => $r) {
                    if (!isset($reindex[$r])) {
                        unset($about->reindex[$l]);
                        continue;
                    }
        
                    $about->reindex[$l] = $reindex[$r];
                }
        
                reset($about->reindex);
                $about->start = current($about->reindex);
                $about->count = count($about->reindex);
        
                if ($about->count < 1) {
                    unset($provides[$file]);
                    continue;
                }
        
                if ($first) {
                    $first = FALSE;
                    $namespace = $about->namespace;
                    $name = $about->name;
                    if ($name === NULL && strncmp($file, "php:", 4) === 0) {
                        $name = "parser";
                    } if ($name === NULL) {
                        $name = basename($file);
                        if (($pos = strrpos($name, ".")) !== FALSE) {
                            $name = substr($name, 0, $pos);
                        }
                    }
                    $invoke = $about->invoke;
                    $constructor = $about->constructor;
                    $privates = $about->privates;
                }
            }
        
            return array($namespace, $name, $inits, $invoke, $constructor, $privates, $this->_walkeach($definitions));

    }

protected function _0($node) { extract($this->_env, EXTR_REFS); return $this->_walk($node);
}
protected function _1($nodes) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walkeach($nodes));
}
protected function _2($node, $code) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($node), $code);
}
protected function _3($node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $this->_walk($node));
}
protected function _4($varname, $node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $varname, $this->_walk($node));
}
protected function _5($file, $node) { extract($this->_env, EXTR_REFS); return array($this->_nodetype(), $self->file_to_init[$file], $this->_walk($node));
}
protected function _6() { extract($this->_env, EXTR_REFS); return $this->_node();
}

}
