<?php
class parse_input
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
        "namespace" => NULL,
        "name" => NULL,
        "init" => NULL,
        "invoke" => NULL,
        "provided" => array(),
        "provides" => array(),
        "n" => 0,
        "final_reindex" => array(),
        "definitions" => array(),
        "file" => NULL,
        "imported_by" => array(),
        "defined" => array(),
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
        case 'phpeg':
            list($_, $_0) = $node;
            $ret = $this->_0($_0);
        break;
        case 'import':
            list($_, $_0, $_1) = $node;
            $ret = $this->_1($_0, $_1);
        break;
        case 'file_':
            list($_, $_0) = $node;
            $ret = $this->_2($_0);
        break;
        case 'namespace':
            list($_, $_0) = $node;
            $ret = $this->_3($_0);
        break;
        case 'name':
            list($_, $_0) = $node;
            $ret = $this->_4($_0);
        break;
        case 'init':
            list($_, $_0) = $node;
            $ret = $this->_5($_0);
        break;
        case 'invoke':
            list($_, $_0, $_1) = $node;
            $ret = $this->_6($_0, $_1);
        break;
        case 'rule':
        case 'macro':
            list($_, $_0) = $node;
            $ret = $this->_7($_0);
        break;
        }

        array_pop($this->_stack);
        return $ret;
    }

    public function __invoke($file, $imported_by, $provided)
    {
        /*$this->_init();*/
        extract($this->_env, EXTR_REFS);
        $self->file = $this->_walk(array("file_", $file));
            $self->imported_by = $imported_by;
        
            $self->provided = $provided;
            if (!empty($self->provided)) {
                $self->n = end(end($self->provided)->reindex) + 1;
            }
        
            list($ok, $tree, $errinfo) = c(new parse_phpeg, file_get_contents($file));
        
            if (!$ok) {
                die("Parse error in file {$self->file} on line {$errinfo->line}, column {$errinfo->column}:\n" .
                    "{$errinfo->context}\n" .
                    str_repeat(" ", $errinfo->column - 1) . "^\n" .
                    "Expected: " . implode(", ", $errinfo->expected) . "\n");
            }
        
            return $this->_walk($tree);

    }

protected function _0($nodes) { extract($this->_env, EXTR_REFS); $this->_walkeach(c(new select_nodes, array("namespace", "name", "init", "invoke"), $nodes));

    c(new check_macros_recursive, $self->file, c(new select_nodes, array("macro"), $nodes));

    $self->definitions = array_values(c(new select_nodes, array("rule", "macro"), $nodes));
    $reindex = array();

    foreach ($self->definitions as $k => $definition) {
        $reindex[$self->defined[] = $this->_walk($definition)] = $self->n + $k;
    }

    $saved_definitions = $self->definitions;

    $self->provides[$self->file] = (object) array(
        "namespace" => $self->namespace,
        "name" => $self->name,
        "init" => $self->init,
        "invoke" => $self->invoke,
        "reindex" => $reindex,
        "start" => $self->n,
        "count" => count($self->definitions),
    );

    $imports = c(new select_nodes, array("import"), $nodes);

    if (!empty($imports)) {
        $this->_walkeach($imports);
    }

    list($doesnt_exist, $reindexed) =
        c(new reindex, $saved_definitions, array_merge($reindex, $self->final_reindex));

    if (!empty($doesnt_exist)) {
        die("These rules needed in file {$self->file}, but does not exist: " .
            implode(", ", $doesnt_exist) . ".\n");
    }

    $self->definitions = array_merge(
        $reindexed,
        array_slice($self->definitions, count($saved_definitions))
    );

    return array($self->provides, $self->definitions);

}
protected function _1($spec, $path) { extract($this->_env, EXTR_REFS); if ($spec === TRUE) {
        $prefix = "";

    } else if (is_string($spec)) {
        $prefix = $spec . ".";

    } else {
        $prefix = basename($path);
        if (($pos = strrpos($prefix, ".")) !== FALSE) {
            $prefix = substr($prefix, 0, $pos);
        }
        $prefix .= ".";
    }

    $file = $this->_walk(array("file_", $path));

    if (isset($self->provided[$file])) {
        $for_final_reindex = $self->provided[$file]->reindex;

    } else {
        if (!isset($self->provides[$file])) {
            list($provides, $definitions) = c(new self,
                $file,
                array_merge($self->imported_by, array($self->file)),
                array_merge($self->provided, $self->provides));

            $self->n = end(end($provides)->reindex) + 1;

            $self->definitions = array_merge($self->definitions, $definitions);
            $self->provides = array_merge($self->provides, $provides);
        }

        $for_final_reindex = $self->provides[$file]->reindex;
    }

    foreach ($for_final_reindex as $from => $to) {
        if (in_array($prefix . $from, $self->defined)) {
            die("Name collision: {$from} imported by {$file} already defined in {$self->file}.\n");
        }
        $self->final_reindex[$prefix . $from] = $to;
    }


}
protected function _2($path) { extract($this->_env, EXTR_REFS); if (strncmp($path, "php:", 4) === 0) {
        return $path;
    }

    if ($self->file !== NULL) {
        $path = dirname($self->file) . "/" . $path;
    }

    $realpath = realpath($path);
    if (!$realpath) {
        die("File {$path} does not exist.\n");
    }

    return $realpath;

}
protected function _3($namespace) { extract($this->_env, EXTR_REFS); if ($self->namespace !== NULL) {
        die("Multiple namespace declarations in file {$self->file}" .
            (!empty($self->imported_by) ? " imported by " . implode(", ", $self->imported_by) : "") .
            ".\n");
    }

    $self->namespace = $namespace;

    return FALSE;

}
protected function _4($name) { extract($this->_env, EXTR_REFS); if ($self->name !== NULL) {
        die("Multiple name declarations in file {$self->file}" .
            (!empty($self->imported_by) ? " imported by " . implode(", ", $self->imported_by) : "") .
            ".\n");
    }

    $self->name = $name;

    return FALSE;

}
protected function _5($code) { extract($this->_env, EXTR_REFS); if ($self->init !== NULL) {
        die("Multiple init declarations in file {$self->file}" .
            (!empty($self->imported_by) ? " imported by " . implode(", ", $self->imported_by) : "") .
            ".\n");
    }

    $self->init = $code;

    return FALSE;

}
protected function _6($arguments, $code) { extract($this->_env, EXTR_REFS); if ($self->invoke !== NULL) {
        die("Multiple invoke declarations in file {$self->file}" .
            (!empty($self->imported_by) ? " imported by " . implode(", ", $self->imported_by) : "") .
            ".\n");
    }

    $self->invoke = array($arguments, $code);

    return FALSE;

}
protected function _7($name) { extract($this->_env, EXTR_REFS); return $name;
}

}
