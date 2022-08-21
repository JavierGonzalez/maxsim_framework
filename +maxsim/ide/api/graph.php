<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <javier.gonzalez@maxsim.tech>


$echo = [];

$file = '+maxsim/log/app/'.str_replace('/', '|', $_GET['file']).'.log';

if ($file AND file_exists($file)) 
    foreach (explode("\n", file_get_contents($file)) AS $line)
        if ($line)
            $echo[] = json_decode($line);

exit_json($echo);