<?php # maxsim — Copyright (c) 2020 Javier González González — javier.gonzalez@maxsim.tech


maxsim();
exit;


function maxsim() {
    global $maxsim;

    $maxsim = [
        'version' => '0.0.1',
        'crono'   => hrtime(true),
        ];

    ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 1);
    ob_start(NULL, 10485760);

    maxsim_get($_SERVER['REQUEST_URI']);

    $route = maxsim_router($_SERVER['REQUEST_URI']);
    __($route);
    foreach ($route AS $key => $value)
        foreach ((array)$value AS $file)
            include($file);
}



function maxsim_get($uri) {
    global $_GET;

    #  URL                       CODE                  INPUT
    #  /                         index.php             $_GET[0] = 'index'; 
    #  /example/abc?p=true       example/abc.php       $_GET[0] = 'example'; $_GET[1] = 'abc'; $_GET['p'] = 'true';
    #  /example?p=true           example.php           $_GET[0] = 'example'; $_GET['p'] = 'true';
    #  /example/abc              example.php           $_GET[0] = 'example'; $_GET[1] = 'abc';
    #  /example/abc              example/index.php     $_GET[0] = 'example'; $_GET[1] = 'abc';


    $url = explode('?', $uri)[0];
    
    if ($url=='/')
        $url = '/index';

    $depth = array_filter(explode('/', $url));

    $_GET = array_merge($depth, $_GET);

}



function maxsim_router($uri) {

    $route = [
        'first'  => [], // Autoload first (*).
        'target' => [], // Content code.
        'after'  => [], // Autoload after (|).
        ];

    $url = explode('?', $uri)[0];
    if ($url=='/')
        $url = '/index';

    $levels = explode('/', $url);

    foreach ($levels AS $id => $level) {
        $level_path[] = $level;

        if (!$ls = glob(($id===0?'*':implode('/',array_filter($level_path)).'/*')))
            break;

        $route = array_merge_recursive((array)$route, (array)maxsim_router_autoload($ls));

        foreach ($ls AS $e)
            if ($e==$levels[$id+1].'.php' OR ($id>0 AND basename($e)=='index.php'))
                $route['target'][] = $e;

        if (count($route['target'])>0)
            break;
    }


    if (!$route['target'])
        $route['target'][] = '404.php';


    return $route;
}


function maxsim_router_autoload($ls) {

    $prefixes = [
        'first' => '*',
        'after' => '|',
        ];

    foreach ($ls AS $e)
        if (strpos(basename($e),'.')!==false)
            foreach ($prefixes AS $key => $value)
                if (substr(basename($e),0,1)==$prefixes[$key])
                    $route[$key][] = $e;


    foreach ($ls AS $e)
        if (strpos(basename($e),'.')===false)
            if (in_array(substr(basename($e),0,1), $prefixes))
                if ($ls_recursive = glob(str_replace('*', '\*', $e).'/*'))
                    $route = array_merge_recursive((array)$route, (array)maxsim_router_autoload($ls_recursive));


    return $route;
}




//////////////////////////////////////////////////////////////////////////////////////



// For debug or benchmark.
function __($echo='', $scroll_down=false) {
	global $maxsim;

    $hrtime = $maxsim['crono'];

    echo '<br />'."\n";
    echo ++$maxsim['___'].'. &nbsp; '.date('Y-m-d H:i:s').' &nbsp; '.implode(' &nbsp; ', maxsim_profiler($hrtime)).' &nbsp; ';


    if (is_string($echo))
        echo $echo;
    else if (is_array($echo) OR is_object($echo))
        echo '<xmp style="background:#EEE;padding:4px;">'.print_r($echo, true).'</xmp>';
    else
        var_dump($echo);
    

    if ($scroll_down) {
        if ($maxsim['___']==1) {
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


function maxsim_profiler($hrtime=false) {
    global $maxsim;

    if (!$hrtime)
        $hrtime = $maxsim['crono'];

    $output[] = number_format((hrtime(true)-$hrtime)/1000/1000,1).' ms';
    
    if (is_numeric($maxsim['sql']['count']))
        $output[] = number_format($maxsim['sql']['count']).' sql';
    
    if (is_numeric($maxsim['rpc']['count']))
        $output[] = number_format($maxsim['rpc']['count']).' rpc';

    $output[] = number_format(memory_get_usage(false)/1024/1024,2).' mb';
    
    return $output;
}
