<?php


function ___($echo='', $scroll_down=false) {
	global $maxsim_debug;

    if (!isset($maxsim_debug['crono']))
    $maxsim_debug['crono'] = $_SERVER['REQUEST_TIME_FLOAT'];

    $microtime = $maxsim_debug['crono'];

    if (!isset($maxsim_debug['count']))
        $maxsim_debug['count'] = 0;

    echo '<br />'."\n";
    echo $maxsim_debug['count']++.'. &nbsp; <span title="'.date('Y-m-d H:i:s').'">'.implode(' &nbsp; ', maxsim_profiler($microtime)).'</span> &nbsp; ';

    if (is_string($echo)) {
        echo '<pre style="display:inline;">'.htmlspecialchars($echo).'</pre>';
    } else if (is_array($echo) OR is_object($echo)) {
        echo '<xmp class="box p-3">'.print_r($echo, true).'</xmp>';
    } else {
        var_dump($echo);
    }

    if ($scroll_down !== false) {
        if ($maxsim_debug['count']==1) {
            if (function_exists('apache_setenv'))
                @apache_setenv('no-gzip', 1);

            ob_end_flush();
            echo '<script>function __sd() { window.scrollTo(0,document.body.scrollHeight); }</script>';
        }

        echo '<script>__sd();</script>';
        ob_flush();
    }

    $maxsim_debug['crono'] = microtime(true);
}


function maxsim_profiler($microtime=false) {
    global $maxsim_debug;

    if (!$microtime)
        $microtime = $_SERVER['REQUEST_TIME_FLOAT'];

    $output[] = number_format((microtime(true)-$microtime)*1000,2).' ms';
    
    if ($maxsim_debug['sql']['count'] ?? null)
        $output[] = number_format($maxsim_debug['sql']['count']).' sql';
    
    if ($maxsim_debug['rpc']['count'] ?? null)
        $output[] = number_format($maxsim_debug['rpc']['count']).' rpc';

    $output[] = number_format(memory_get_usage(false)/1024).' kb';
    
    return $output;
}

