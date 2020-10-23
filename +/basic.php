<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


function every($seconds=60, $id=0) {
    global $every_last;

    if (time() >= $every_last[$id]+$seconds)
        return $every_last[$id] = time();

    return false;
}


function redirect($url='/') {
    header('Location: '.$url);
    exit;
}


function shell($command) {
    return trim(shell_exec($command.' 2>&1'));
}


function injection_filter($danger_input) {
    $output = trim(strip_tags($danger_input));
    if (get_magic_quotes_gpc())
        $output = stripslashes($output);
    return $output;
}