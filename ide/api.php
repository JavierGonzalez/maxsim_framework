<?php


$_GET['file'] = 'index.php';

if ($_GET[2]=='file' AND $_GET['file'])
    echo file_get_contents($_GET['file']);



exit;