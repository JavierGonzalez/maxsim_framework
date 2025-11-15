<?php

/** HTTP redirect and exit */
function redirect(string $url = '/', array $post = []): void {
    if ($post === []) {
        header('Location: '.$url);
        exit;
    }
    echo '<html><head><meta charset="UTF-8"><title></title></head><body onload="document.forms[0].submit()">';
    echo '<form method="post" action="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'">';
    foreach ($post AS $key => $value) {
        if (!is_string($value))
            continue;    
        echo '<input type="hidden" name="'.$key.'" value="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'">';
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

/** Linux command execution */
function shell(string $cmd): mixed {
    $output = shell_exec($cmd.' 2>&1'); // $cmd must be sanitized to prevent shell injection
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

/** To convert `12345` to `12 K` */
function num_to_human(mixed $n, int $decimals = 0): string {
    $units = ['', ' K', ' M', ' G', ' T', ' P'];
    if ($n == 0) return '0';
    $sign = $n < 0 ? '-' : '';
    $v = abs($n);
    $i = 0;
    while ($v >= 1000 && $i < count($units) - 1) { $v /= 1000; $i++; }
    return $sign . number_format($v, $decimals, '.', '') . $units[$i];
}


function format_time_diff(int|string $date_time, string $time_zone = 'UTC'): string {
    if (is_numeric($date_time)) 
        $date_time = date('Y-m-d H:i:s', (int)$date_time);

    $now = new DateTime('now',      new DateTimeZone($time_zone));
    $dt  = new DateTime($date_time, new DateTimeZone($time_zone));
    $diff = $now->getTimestamp() - $dt->getTimestamp();

    if ($diff === 0) 
        return '0s';
    $sign = $diff < 0 ? '-':'';
    $diff = abs($diff);

    $days_total = intdiv($diff, 86400);

    if ($diff >= 31536000) { // y d
        $years = intdiv($days_total, 365);
        $days  = $days_total - $years * 365;
        return $sign.$years.'y'.($days ? ' '.$days.'d':'');
    }

    if ($diff >= 86400) { // d h
        $days  = $days_total;
        $hours = intdiv($diff % 86400, 3600);
        return $sign.$days.'d'.($hours ? ' '.$hours.'h':'');
    }

    if ($diff >= 3600) { // h mm
        $hours   = intdiv($diff, 3600);
        $minutes = intdiv($diff % 3600, 60);
        return $sign.$hours.'h'.($minutes ? ' '.str_pad((string)$minutes, 2, '0', STR_PAD_LEFT).'m':'');
    }

    if ($diff >= 60) { // m ss
        $minutes = intdiv($diff, 60);
        $seconds = $diff % 60;
        return $sign.$minutes.'m'.($seconds ? ' '.str_pad((string)$seconds, 2, '0', STR_PAD_LEFT).'s':'');
    }

    return $sign.$diff.'s';
}

