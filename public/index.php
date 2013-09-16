<?php
session_start();
header("Content-Type: text/plain");
echo print_r($_SESSION);
