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
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($input)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        list($provides, $definitions) = c(new parse_input, $input, array(), array());
        
            $definitions = c(new expand_macros, $definitions);
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
        
            foreach ($provides as $k => $about) {
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
                    unset($provides[$k]);
                    continue;
                }
        
                if ($first) {
                    $first = FALSE;
                    $namespace = $about->namespace;
                    $name = $about->name;
                    if ($name === NULL && strncmp($k, "php:", 4) === 0) {
                        $name = "parser";
                    } if ($name === NULL) {
                        $name = basename($k);
                        if (($pos = strrpos($name, ".")) !== FALSE) {
                            $name = substr($name, 0, $pos);
                        }
                    }
                    $invoke = $about->invoke;
                }
        
                if ($about->init) {
                    $inits[] = array(array_values($about->reindex), $about->init);
                }
            }
        
            return array($namespace, $name, $inits, $invoke, $this->_walkeach($definitions));

    }

protected function _0($name, $node) { extract($this->_env, EXTR_REFS); return $node;
}

}
