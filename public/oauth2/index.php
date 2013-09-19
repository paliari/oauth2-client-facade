<?php
header("Content-Type: text/plain");
session_start();
print_r($_SESSION);
session_destroy();
//echo 'SERVER: '.print_r($_SERVER).PHP_EOL;
