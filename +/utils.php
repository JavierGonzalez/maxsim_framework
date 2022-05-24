<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


function num($number = null, int $decimals = 0) { 

    if (!is_numeric($number))
        return '';

    return number_format($number, $decimals, '.', ',');
}


function shell(string $command) {
    return trim(shell_exec($command.' 2>&1'));
}


function datetime(int $days = 0, $hours = ' H:i:s') {
    return date('Y-m-d'.$hours ?? '', time() + (86400 * $days));
}


function every(int $seconds = 60, int $id = 0) {
    global $every_last;

    if (time() >= $every_last[$id]+$seconds)
        return $every_last[$id] = time();

    return false;
}


function redirect(string $url = '/') {
    header('Location: '.$url);
    exit;
}


function injection_filter(string $danger_input) {
    $output = trim(strip_tags($danger_input));
    if (get_magic_quotes_gpc())
        $output = stripslashes($output);
    return $output;
}


function text_to_title(string $title) {
    $title = str_replace(['-', '_', '/'], ' ', $title);
    return ucwords(trim($title));
}
