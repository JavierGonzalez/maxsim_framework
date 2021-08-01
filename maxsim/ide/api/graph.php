<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


$maxsim['output'] = 'json';

$echo = [];

$file = 'maxsim/logs/app/'.str_replace('/', '|', $_GET['file']).'.log';

if ($file AND file_exists($file)) 
    foreach (explode("\n", file_get_contents($file)) AS $line)
        if ($line)
            $echo[] = json_decode($line);
