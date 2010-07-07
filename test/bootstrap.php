<?php
foreach (glob(dirname(__FILE__) . '/../build/*.php') as $file) {
    require_once $file;
}
