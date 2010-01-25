<?php
function generate_bootstrap(phpeg $phpeg)
{
    $generator = new phpgen($phpeg);
    return $generator->generate();
}
