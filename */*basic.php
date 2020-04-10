<?php


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



function print_r2($print, $echo=true) {
    $html = '<xmp style="background:#EEE;padding:4px;">'.print_r($print, true).'</xmp>';
    if ($echo===true)
        echo $html;
    else
        return $html;
}



function injection_filter($danger_input) {
    $output = trim(strip_tags($danger_input));
    if (get_magic_quotes_gpc())
        $output = stripslashes($output);
    return $output;
}