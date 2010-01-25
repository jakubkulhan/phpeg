<?php
class phpeg
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $name;

    /** @var array */
    private $rules;

    /** @var array */
    private $leftmost_applications = array();

    /** @var array */
    private $ilr_chains = NULL;

    /** @var array */
    private $dlr = NULL;

    /**
     * Initializes instance
     * @param string
     * @param string
     * @param array
     */
    public function __construct($namespace, $name, array $rules)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->rules = $rules;

        foreach ($this->rules as $name => $tree) {
            $this->leftmost_applications[$name] = $this->la($tree);
        }
    }

    /** @return array */
    public function getNonexistentRules()
    {
        $nr = array();

        foreach ($this->rules as $name => $tree) {
            $nr = array_merge($nr, $this->nr($tree));
        }

        return array_keys(array_flip($nr));
    }

    /** @return array */
    private function nr($tree)
    {
        $rest = array_slice($tree, 1);
        $ret = NULL;

        switch ($tree[0]) {
            case 'app':
                if (!isset($this->rules[$rest[0]])) {
                    $ret = $rest[0];
                }
            break;

            case 'all':
                $ret = array();
                foreach ($rest as $exp) {
                    $ret = array_merge($ret, $this->nr($exp));
                }
            break;

            case 'opt':
            case 'mr0':
            case 'mr1':
                $ret = $this->nr($rest[0]);
            break;

            case 'act':
            case 'bnd':
                $ret = $this->nr($rest[1]);
            break;

            case 'fst':
                $ret = array();
                foreach ($rest as $exp) {
                    $ret = array_merge($ret, $this->nr($exp));
                }
            break;

            case 'and':
            case 'spr':
            case 'not':
            case 'lit':
            case 'rng':
            case 'any':
                $ret = NULL;
            break;

            default:
                die('nr: ' . var_export($tree, TRUE));

        }

        return array_keys(array_flip(array_filter((array) $ret)));
    }

    /** @return array */

    /** @return array [rule name => chain]*/
    public function getIndirectlyLeftRecursive()
    {
        if ($this->ilr_chains === NULL) {
            $ilr = array();

            foreach ($this->rules as $name => $tree) {
                $ilr = array_merge($ilr, $this->ilr(array($name)));
            }

            $this->ilr_chains = $ilr;
        }

        return $this->ilr_chains;
    }

    /** @return array */
    private function ilr(array $chain)
    {
        $last = end($chain);
        $flipped_chain = array_flip($chain);
        $leftmost_applications = array_flip($this->leftmost_applications[$last]);
        unset($leftmost_applications[$last]);

        if (empty($leftmost_applications)) { return array(); }
        else {
            $ret = array();

            foreach ($leftmost_applications as $name => $_) {
                if (isset($flipped_chain[$name])) {
                    $ret[] = $chain;
                } else {
                    $ret = array_merge($ret, $this->ilr(array_merge($chain, array($name))));
                }
            }

            return $ret;
        }
    }

    /** @return array */
    public function getDirectlyLeftRecursive()
    {
        if ($this->dlr === NULL) {
            $dlr = array();

            foreach ($this->rules as $name => $tree) {
                if (in_array($name, $this->leftmost_applications[$name])) {
                    $dlr[] = $name;
                }
            }

            $this->dlr = $dlr;
        }

        return $this->dlr;
    }

    /** @return string */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return array */
    public function getRules()
    {
        return $this->rules;
    }

    /** @return string */
    public function getStartingRuleName()
    {
        reset($this->rules);
        return key($this->rules);
    }

    /** @return array */
    public function getRule($name)
    {
        if (!isset($this->rules[$name])) { return NULL; }
        return $this->rules[$name];
    }

    /** @return array */
    private function la($tree)
    {
        $rest = array_slice($tree, 1);
        $ret = NULL;

        switch ($tree[0]) {
            case 'app':
                $ret = $rest[0];
            break;

            case 'all':
            case 'opt':
            case 'mr0':
            case 'mr1':
                $ret = $this->la($rest[0]);
            break;

            case 'act':
            case 'bnd':
                $ret = $this->la($rest[1]);
            break;

            case 'fst':
                $ret = array();
                foreach ($rest as $exp) {
                    $ret = array_merge($ret, $this->la($exp));
                }
            break;

            case 'and':
            case 'spr':
            case 'not':
            case 'lit':
            case 'rng':
            case 'any':
                $ret = NULL;
            break;

            default:
                die('la: ' . var_export($tree, TRUE));
        }

        return array_keys(array_flip(array_filter((array) $ret)));
    }
}
