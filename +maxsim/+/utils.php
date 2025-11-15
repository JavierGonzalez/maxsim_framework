<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>


/** header location redirection and exit */
function redirect(string $url = '/', bool | array $post = false): void {

    if ($post === false) {
        header('Location: '.$url);
        exit;
    }
    
    echo '<html><head><meta charset="UTF-8"><title>Redirect...</title></head><body onload="document.forms[0].submit()">';
    echo '<form method="post" action="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';
    foreach ($post AS $key => $value) {
        if (is_string($value))
            echo '<input type="hidden" name="'.htmlspecialchars($key, ENT_QUOTES, 'UTF-8').'" value="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'">';
    }
    echo '</form><noscript><button type="submit">Continue</button></noscript></body></html>';
    exit;
}

/** ob_end_clean(), echo JSON from array and exit */
function exit_json(array $echo): void {
    ob_end_clean();
    echo json_encode($echo, JSON_PRETTY_PRINT);
    exit;
}

/** number format for humans */
function num(mixed $number, int $decimals = 0): string { 

    if (!is_numeric($number))
        return '';

    return number_format($number, $decimals, '.', ',');
}

/** $linux_command must be sanitized to prevent shell injection */
function shell(string $cmd): mixed {
    $output = shell_exec($cmd.' 2>&1');

    if (!is_string($output))
        return false;
    
    $output = trim($output);

    if (substr($output, 0, 7) === 'sh: 1: ') {        
        if (substr($output, -11) === ': not found')
            return false;

        if (substr($output, -19) === ': Permission denied')
            return false;
    }
    
    return $output;
}

/** To escape shell commands and prevent cmd injection */
function shell_injection_escape(string $danger_input): string {
    $output = trim(strip_tags($danger_input));
    if (get_magic_quotes_gpc())
        $output = stripslashes($output);
    return $output;
}

/** To convert `12345` to `12 K` */
function num_to_human(mixed $num, int $decimals=0, bool $show_zero = false): string {

    if (!is_numeric($num))
        return '';

    if ($show_zero AND $num === 0)
        return '0';

    $len = strlen($num);
    
    if ($len > 15) 
        return num($num / 1_000_000_000_000_000, $decimals).' P';
    else if ($len > 12) 
        return num($num /     1_000_000_000_000, $decimals).' T';
    else if ($len > 9) 
        return num($num /         1_000_000_000, $decimals).' G';
    else if ($len > 6) 
        return num($num /             1_000_000, $decimals).' M';
    else if ($len > 3) 
        return num($num /                 1_000, $decimals).' K';
    else 
        return num($num, $decimals);
}



/** Convert "Y-m-d H:i:s" into "XXh YYm ZZs" */
function format_time_diff(null | string $date_time = '0000-00-00 00:00:00', string $time_zone = 'UTC'): string {

    if ($date_time === '' OR !is_string($date_time))
        return '';

    $now     = new DateTime('now',      new DateTimeZone($time_zone));
    $post_dt = new DateTime($date_time, new DateTimeZone($time_zone));
    $diff_seconds = $now->getTimestamp() - $post_dt->getTimestamp();
    if ($diff_seconds < 0) {
        $diff_seconds = 0;
    }

    $years   = floor($diff_seconds / 31536000);
    $days    = floor($diff_seconds / 86400);
    $hours   = floor(($diff_seconds % 86400) / 3600);
    $minutes = floor(($diff_seconds % 3600) / 60);
    $seconds = $diff_seconds % 60;

    $parts = [];
    if ($years > 0) {
        $parts[] = $years.'y';
    }
    if ($days > 0) {
        $parts[] = $days.'d';
    }
    if ($hours > 0) {
        $parts[] = $hours.'h';
    }
    if ($minutes > 0) {
        $parts[] = str_pad($minutes, 2, '0', STR_PAD_LEFT).'m';
    }
    if ($seconds > 0) {
        $parts[] = str_pad($seconds, 2, '0', STR_PAD_LEFT).'s';
    }

    return $parts ? implode(' ', $parts) : '0s';
}