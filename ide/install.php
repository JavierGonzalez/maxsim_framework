<?php

$config['autoload']['autoload'][] = maxsim_absolute(__DIR__).'injection.php'; 

maxsim_config($config);

/*
maxsim_autoload_file('injection.php');

function maxsim_autoload_file($file) {

    if (file_exists($file))

}
*/