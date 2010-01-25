#!/bin/sh
SCRIPTSDIR="`dirname $0`"
BINDIR="$SCRIPTSDIR/../bin"
LIBDIR="$SCRIPTSDIR/../lib"
PACC="`which pacc 2>/dev/null`"

if ! [ -x "$PACC" ]; then
    echo "I need pacc in \$PATH."
    exit 1
fi

$PACC -i "$LIBDIR/parse/bootstrap.y" -fo "$LIBDIR/parse/bootstrap.php"
$BINDIR/phpeg -i "$LIBDIR/parse/parse_php.bootstrap.peg" -fo "$LIBDIR/parse/php.php"
$BINDIR/phpeg -i "$LIBDIR/parse/parse_php.php.peg" -fo "$LIBDIR/parse/php.php"
rm -f "$LIBDIR/parse/bootstrap.php"
