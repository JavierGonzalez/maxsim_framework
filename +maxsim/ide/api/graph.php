<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


$echo = [];

$file = '+maxsim/log/app/'.str_replace('/', '|', $_GET['file']).'.log';

if ($file AND file_exists($file)) 
    foreach (explode("\n", file_get_contents($file)) AS $line)
        if ($line)
            $echo[] = json_decode($line);

exit_json($echo);