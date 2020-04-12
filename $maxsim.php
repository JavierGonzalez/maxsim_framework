<?php # maxsim.tech — Copyright (c) 2020 Javier González González — gonzo@maxsim


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

    maxsim_get($_SERVER['REQUEST_URI']);

    ob_start();

    foreach (maxsim_router($_SERVER['REQUEST_URI']) AS $value)
        foreach ($value AS $file)
            include($file);
}


function maxsim_get(string $uri) {
    global $_GET;

    $url = explode('?', $uri)[0];
    
    if ($url=='/')
        $url = '/index';

    $depth = array_filter(explode('/', $url));

    $_GET = array_merge($depth, $_GET);
}


function maxsim_router(string $uri) {

    $route = [
        'first'  => [], // Autoload first (*).
        'target' => [], // Application code.
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

        $route = array_merge_recursive($route, maxsim_autoload($ls));

        foreach ($ls AS $e)
            if ($e==$levels[$id+1].'.php' OR ($id>0 AND basename($e)=='index.php'))
                $route['target'][] = $e;

        if (count($route['target'])>0)
            break;
    }

    if (!$route['target'])
        $route['target'][] = '404.php';

    return (array) $route;
}


function maxsim_autoload(array $ls) {

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
                    $route = array_merge_recursive((array)$route, maxsim_autoload($ls_recursive));

    return (array) $route;
}