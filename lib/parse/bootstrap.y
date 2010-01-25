grammar PhpegBootstrapParser

option algorithm = "LR";

option header = '
    /**
     * Bootstrap parser error
     */
    class PhpegBootstrapParserError extends Exception
    {
        /** @var array Source lines*/
        private $lines;

        /** @var int Line number (starting at 1) */
        private $l;

        /** @var int Position at current line (starting at 1) */
        private $pos;

        /**
         * Initializes error
         * @param string
         * @param int
         * @param int
         * @param Exception
         */
        public function __construct($parsed_string, $p, Exception $previous = NULL)
        {
            $this->p = $p;

            $this->lines = explode("\n", str_replace(array("\r\n", "\r"), "\n", $parsed_string));

            $pre = substr($parsed_string, 0, $p);
            $pre_lines = explode("\n", str_replace(array("\r\n", "\r"), "\n", $pre));
            $this->l = count($pre_lines);

            $this->pos = strlen(end($pre_lines)) + 1;

            $message = "Parse error on line {$this->l} at position {$this->p}:\n" .
                       "{$this->l}: " . $this->getCurrentLineString() . "\n" .
                       str_repeat(" ", strlen((string) $this->l) + $this->p + 1) . "^\n";

            parent::__construct($message, 0, $previous);
        }

        /** @return int */
        public function getLineNumber()
        {
            return $this->l;
        }

        /** @return int */
        public function getPosition()
        {
            return $this->pos;
        }

        /** @return int */
        public function getP()
        {
            return $this->p;
        }

        /** @return string */
        public function getCurrentLineString()
        {
            return $this->lines[$this->l - 1];
        }

        /** @return string */
        public function __toString()
        {
            return $this->getMessage();
        }
    }

    function parse_bootstrap($s)
    {
        if (!class_exists("PhpegBootstrapParser")) {
';

option footer = '
        }

        $parser = new PhpegBootstrapParser;
        return $parser->parse($s);
    }
';

option inner = {
    const ID = 1, STRING = 2, RANGE = 3, ACTION = 4, SPECIAL = 5;

    /** @var string */
    private $parsed_string;

    /** @var string */
    private $string;

    /** @var int */
    private $p;

    /** @var stdClass */
    private $token;

    /** @var array */
    private static $tokens = array(
        "#^([a-zA-Z][a-zA-Z0-9_]*)#S" => self::ID,
        "#^\"(([^\"\\\\]|\\\\[\"\\\\nrt]|\\\\x[0-9a-fA-F]{2})*)\"#S" => self::STRING,
        "#^\[((([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2})-([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2})|([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2}))+)\]#S" => self::RANGE,
        "#^(=|;|/|:|&|!|%|->|\?|\*|\+|\(|\)|\.)#S" => self::SPECIAL,
        "#^\{()#S" => self::ACTION,
    );

    /** @var array */
    private static $escapes = array(
        "\\\\"  => "\\",
        "\\\""  => "\"",
        "\\n"   => "\n",
        "\\r"   => "\r",
        "\\t"   => "\t",
        "\\]"   => "]",
    );

    /**
     * Parses given string
     * @param string
     * @return array
     */
    public function parse($string)
    {
        try {
            try {
                $this->parsed_string = $this->string = $string;
                $this->line = $this->position = 1;
                $this->_nextToken();
                return array(TRUE, $this->doParse(), 0);

            } catch (PhpegBootstrapParserError $e) {
                throw $e;
            } catch (Exception $e) {
                throw new PhpegBootstrapParserError($this->parsed_string, $this->p, $e);
            }
        } catch (PhpegBootstrapParserError $e) {
            return array(FALSE, NULL, $e->getP());
        }
    }

    /** @return array */
    private function makeRange($s)
    {
        $range = array();
        foreach (explode("\xff",
                                preg_replace(
                                    "#\xff([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2})-([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2})\xff#S",
                                    "\xff$1\x00$2\xff",
                                    preg_replace(
                                        "#(([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2})-([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2})|([^\\[\\]\\\\]|\\\\[\\[\\]\\\\nrt]|\\\\x[0-9a-fA-F]{2}))#S",
                                        "\xff$1\xff", $s
                                    )
                                )
            ) as $part)
        {
            if (empty($part)) { continue; }
            $r = explode("\x00", $part);
            $r_new = array();
            foreach ($r as $c) {
                if (strncmp($c, "\x", 2) === 0) {
                    $r_new[] = hexdec(substr($c, 2));
                } else if ($c[0] === "\\") {
                    $r_new[] = ord(self::$escapes[$c]);
                } else {
                    $r_new[] = ord($c);
                }
            }

            if (count($r_new) === 1) { $range[] = $r_new[0]; }
            else { $range[] = $r_new; }

        }

        return $range;
    }

    /** @return string */
    private function makeString($s)
    {

        $string = "";
        $parts = explode("\xff", preg_replace("#(\\\\[\"\\\\nrt]|\\\\x[0-9a-fA-F]{2})#S", "\xff$1\xff", $s));

        for ($i = 0, $l = count($parts); $i < $l; ++$i) {
            if (!($i & 1)) { $string .= $parts[$i]; }
            else {
                $part = $parts[$i];
                if (strncmp("\x", $part, 2) === 0) {
                    $string .= chr(hexdec(substr($part, 2)));
                } else {
                    $string .= self::$escapes[$part];
                }
            }
        }

        return $string;
    }

}

@currentToken {
    return $this->token;
}

@currentTokenType {
    return $this->token->type;
}

@currentTokenLexeme {
    return $this->token->lexeme;
}

@nextToken {
    if (preg_match('#^\s+#Ss', $this->string, $m)) {
        $this->string = substr($this->string, strlen($m[0]));
        $this->p += strlen($m[0]);
    }

    if (empty($this->string)) {
        $this->token = (object) array(
            'type' => NULL,
            'lexeme' => NULL,
        );

        return ;
    }


    $ok = FALSE;

    foreach (self::$tokens as $regex => $type) {
        if (preg_match($regex, $this->string, $m)) {
            $this->token = (object) array(
                'type' => $type, 
                'lexeme' => $m[1],
            );

            if ($type === self::ACTION) {
                $offset = 0;
                do {
                    if (($rbrace = strpos($this->string, '}', $offset)) === FALSE) {
                        $this->token->lexeme = substr($this->string, 1);
                        return ;
                    }

                    $offset = $rbrace + 1;
                    $code = substr($this->string, 0, $rbrace + 1);
                    $test = preg_replace('#"(\\\\"|[^"])*$
                                          |"(\\\\"|[^"])*"
                                          |\'(\\\\\'|[^\'])*\'
                                          |\'(\\\\\'|[^\'])*$
                                          #Sx', '', $code);

                } while (substr_count($test, '{') !== substr_count($test, '}'));

                $this->token->lexeme = substr($code, 1, strlen($code) - 2);
                $m[0] = $code;
            }

            $ok = TRUE;

            break;
        }
    }

    if (!$ok) {
        throw new PhpegBootstrapParserError($this->parsed_string, $this->p);
    }

    $this->p += strlen($m[0]);

    $this->string = substr($this->string, strlen($m[0]));
}

toplevel 
    : rules
    ;

rules 
    : rule { $$ = array($1[0] => $1[1]); }
    | rule rules 
        {
            list($n, $e) = $1;

            if (!isset($2[$n])) {
                $$ = array($n => $e);

            } else {
                if ($2[$n][0] === 'fst') { $2[$n] = array_slice($2[$n], 1); }
                else { $2[$n] = array($2[$n]); }

                if ($e[0] === 'fst') { $e = array_slice($e, 1); }
                else { $e = array($e); }

                $2[$n] = array_merge(array('fst'), $e, $2[$n]);
                $$ = array($n => $2[$n]);
            }
                
            $$ = array_merge($$, $2);
        }
    ;

rule
    : ID '=' expression ';'     { $$ = array($1->lexeme, $3); }
    ;

expression
    : sequences                 { $$ = count($1) > 1 ? array_merge(array('fst'), $1) : $1[0]; }
    ;

sequences
    : sequence                  { $$ = array($1); }
    | sequence '/' sequences    { $$ = array_merge(array($1), $3); }
    ;

sequence
    : prefixes                  { $$ = count($1) > 1 ? array_merge(array('all'), $1) : $1[0]; }
    | prefixes '->' ACTION      { $$ = array('act', $3->lexeme, count($1) > 1 ? array_merge(array('all'), $1) : $1[0]); }
    ;

prefixes
    : prefix                    { $$ = array($1); }
    | prefix prefixes           { $$ = array_merge(array($1), $2); }
    ;

prefix
    : suffix
    | ID ':' suffix             { $$ = array('bnd', $1->lexeme, $3); }
    | '&' suffix                { $$ = array('and', $2); }
    | '!' suffix                { $$ = array('not', $2); }
    ;

suffix
    : primary
    | primary '?'               { $$ = array('opt', $1); }
    | primary '*'               { $$ = array('mr0', $1); }
    | primary '+'               { $$ = array('mr1', $1); }
    ;

primary
    : ID                        { $$ = array('app', $1->lexeme); }
    | '(' expression ')'        { $$ = $2; }
    | STRING                    { $$ = array('lit', $this->makeString($1->lexeme)); }
    | RANGE                     { $$ = array_merge(array('rng'), $this->makeRange($1->lexeme)); }
    | '.'                       { $$ = array('any'); }
    ;
