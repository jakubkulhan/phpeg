# phpeg – PEG for PHP

[PEG](http://en.wikipedia.org/wiki/Parsing_expression_grammar) parser generator for PHP.

## Get ready

Firstly you will need [pacc](http://github.com/jakubkulhan/pacc) for bootstrapping. Install *pacc* according to instructions at referred page to `$PATH`. Then run:

    $ ./scripts/bootstrap.sh

Now you can use executable `bin/phpeg`. To make independent executable (that does not depends on its location in filesystem), use `scripts/compile.php`. For example to install *phpeg* into `/usr/bin`:

    # ./scripts/compile.php /usr/bin/pacc

## Write PEGs

*phpeg* uses [PEG](http://en.wikipedia.org/wiki/Parsing_expression_grammar) with some changes:

- Parsing rules has form `A = e` instead of `A ← e`.
- *phpeg* supports variables binding – `varname:e`.
- *phpeg* supports semantic predicates – `?{ /* predicate expression */ }`.
- Semantic actions are denoted by `->`.

Check out `lib/parse/*.peg` for examples.

## License

The MIT license

    Copyright (c) 2010 Jakub Kulhan <jakub.kulhan@gmail.com>

    Permission is hereby granted, free of charge, to any person
    obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without
    restriction, including without limitation the rights to use,
    copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following
    conditions:

    The above copyright notice and this permission notice shall be
    included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
    OTHER DEALINGS IN THE SOFTWARE.
