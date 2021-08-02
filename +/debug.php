<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


function ___($echo='', $echo2=false, $scroll_down=false) {
	global $maxsim;

    if (!isset($maxsim['debug']['crono']))
        $maxsim['debug']['crono'] = $_SERVER['REQUEST_TIME_FLOAT'];

    $microtime = $maxsim['debug']['crono'];

    echo '<br />'."\n";
    echo @++$maxsim['debug']['count'].'. &nbsp; <span title="'.date('Y-m-d H:i:s').'">'.implode(' &nbsp; ', profiler($microtime)).'</span> &nbsp; ';

    if (is_array($echo2)) {
        echo $echo;
        echo '<xmp style="background:#EEE;padding:4px;">'.print_r($echo2, true).'</xmp>';
    } else if (is_string($echo)) {
        echo $echo;
    } else if (is_array($echo) OR is_object($echo)) {
        echo '<xmp style="background:#EEE;padding:4px;">'.print_r($echo, true).'</xmp>';
    } else {
        var_dump($echo);
    }

    if ($scroll_down) {
        if ($maxsim['debug']['count']==1) {
            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', 1);
            }

            ob_end_flush();
            echo '<script>function __sd() { window.scrollTo(0,document.body.scrollHeight); }</script>';
        }

        echo '<script>__sd();</script>';
        flush();
        ob_flush();
    }

    $maxsim['debug']['crono'] = microtime(true);
}


function profiler($microtime=false) {
    global $maxsim, $__sql, $__rpc;

    if (!$microtime)
        $microtime = $_SERVER['REQUEST_TIME_FLOAT'];

    $output[] = number_format((microtime(true)-$microtime)*1000,2).' ms';
    
    if (is_numeric(@$__sql['count']))
        $output[] = number_format($__sql['count']).' sql';
    
    if (is_numeric(@$__rpc['count']))
        $output[] = number_format($__rpc['count']).' rpc';

    $output[] = number_format(memory_get_usage(false)/1024).' kb';
    
    return $output;
}



function maxsim_timing() {
    global $maxsim;
    

    if (isset($maxsim['debug']['timing']['app']))
        $maxsim['debug']['timing']['template'] = microtime(true);
    else
        $maxsim['debug']['timing']['app'] = microtime(true);
    
    
    $microtime_last = $_SERVER['REQUEST_TIME_FLOAT'];
    
    $debug_log_target = ['time' => time()];

    $id = 0;
    foreach ((array) $maxsim['debug']['timing'] AS $key => $value) { 
        if ($value > 1000000000) {
            $debug_log_target[$key] = round(($value-$microtime_last)*1000, 2);
            $server_timing[] = ++$id.';dur='.$debug_log_target[$key].';desc="'.$key.'"';
            $microtime_last = $value;
        } else {
            $debug_log_target[$key] = round($value,2);
            $server_timing[] = $key.';dur='.$debug_log_target[$key].';desc="'.$key.'"';
        }
    }

    $debug_log_target['RAM'] = number_format(memory_get_usage(false)/1024);
    $debug_log_target['TOTAL'] = round((microtime(true)-$_SERVER['REQUEST_TIME_FLOAT'])*1000, 2);
    
    
    $server_timing[] = '99;desc="RAM '.$debug_log_target['RAM'].' kb"';
    $server_timing[] = 'TOTAL;dur='.$debug_log_target['TOTAL'];
    
    $debug_log_target['url'] = $_SERVER['REQUEST_URI'];
    
    if (http_response_code() === 200) {
        chdir($_SERVER['DOCUMENT_ROOT']); // Working directory of the script can change inside the shutdown function under some web servers, e.g. Apache.
        file_put_contents('maxsim/logs/app/'.str_replace('/', '|', $maxsim['app']).'.log', json_encode($debug_log_target)."\n", FILE_APPEND);
    }

    header('server-timing: '.implode(', ', (array)$server_timing));
}

header_register_callback('maxsim_timing');