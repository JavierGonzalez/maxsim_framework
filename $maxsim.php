<?php # maxsim.tech — Copyright (c) 2020 Javier González González — ALL RIGHTS RESERVED 


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
            if (!$route['target'] AND basename($e)==$levels[$id+1].'.php')
                $route['target'][] = $e;
        
        foreach ($ls AS $e)
            if (!$route['target'] AND $id>0 AND basename($e)=='index.php')
                $route['target'][] = $e;

        if (count($route['target'])>0)
            break;
    }

    if (file_exists('$maxsim.json'))
        $route = array_merge_recursive($route, (array) maxsim_config()['autoload']);

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
                if ($ls_recursive = glob(str_replace('*','\*',$e).'/*'))
                    $route = array_merge_recursive((array)$route, maxsim_autoload($ls_recursive));

    return (array) $route;
}


function maxsim_config(array $config_input=[], string $config_file='$maxsim.json') {

    if (file_exists($config_file))
        $config = (array)json_decode(file_get_contents($config_file), true);

    if (count($config_input)>0) {
        $config = array_merge((array)$config, $config_input);
        $config = array_filter($config, static function($a){return $a!==null;});
        file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));
    }

    return (array) $config;
}


function maxsim_relative_dir(string $dir) {
    return (string) str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $dir).'/';
}