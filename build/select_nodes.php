<?php
class select_nodes
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
        "select" => array()
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
        default:
            $ret = $this->_0();
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($select, $nodes)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        $self->select = $select;
            return array_filter($this->_walkeach($nodes));

    }

protected function _0() { extract($this->_env, EXTR_REFS); if (in_array($this->_nodetype(), $self->select)) {
        return $this->_node();
    }

    return FALSE;

}

}
