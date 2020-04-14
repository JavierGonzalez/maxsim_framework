<?php


// For debug or benchmark.
function __($echo='', $scroll_down=false) {
	global $maxsim;

    $hrtime = $maxsim['crono'];

    echo '<br />'."\n";
    echo ++$maxsim['debug_count'].'. &nbsp; '.date('Y-m-d H:i:s').' &nbsp; '.implode(' &nbsp; ', profiler($hrtime)).' &nbsp; ';


    if (is_string($echo))
        echo $echo;
    else if (is_array($echo) OR is_object($echo))
        echo '<xmp style="background:#EEE;padding:4px;">'.print_r($echo, true).'</xmp>';
    else
        var_dump($echo);
    

    if ($scroll_down) {
        if ($maxsim['debug_count']==1) {
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

    $maxsim['crono'] = hrtime(true);
}



function print_r2($print, $echo=true) {
    $html = '<xmp style="background:#EEE;padding:4px;">'.print_r($print, true).'</xmp>';
    if ($echo===true)
        echo $html;
    else
        return $html;
}


function profiler($hrtime=false) {
    global $maxsim;

    if (!$hrtime)
        $hrtime = $maxsim['crono'];

    $output[] = number_format((hrtime(true)-$hrtime)/1000/1000,2).' ms';
    
    if (is_numeric($maxsim['sql']['count']))
        $output[] = number_format($maxsim['sql']['count']).' sql';
    
    if (is_numeric($maxsim['rpc']['count']))
        $output[] = number_format($maxsim['rpc']['count']).' rpc';

    $output[] = number_format(memory_get_usage(false)/1024).' kb';
    
    return $output;
}

