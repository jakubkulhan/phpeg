<?php
function generate_php(phpeg $phpeg)
{
    $generator = new phpgen($phpeg);
    return $generator->generate();
}
