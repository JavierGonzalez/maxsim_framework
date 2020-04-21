<?php # maxsim.tech — Copyright (c) 2020 Javier González González — ALL RIGHTS RESERVED 


maxsim();
exit;


function maxsim() {
    global $maxsim;

    $maxsim = [
        'version' => '0.5.0',
        'crono'   => hrtime(true),
        ];

    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);


    $route = maxsim_router($_SERVER['REQUEST_URI']);
    
    $maxsim['target'] = $route['target'][0];
    $maxsim['target_level'] = count(explode('/', $route['target'][0]))-1;

    maxsim_get($_SERVER['REQUEST_URI'], $maxsim['target_level']);

    ob_start();

    foreach ($route AS $value)
        foreach ($value AS $file)
            maxsim_include($file);
}


function maxsim_get(string $uri, int $target_level=0) {
    global $_GET;

    $url = explode('?', $uri)[0];
    if ($url=='/')
        $url = '/index';

    $levels = array_filter(explode('/', $url));
    foreach ($levels AS $level => $name)
        if ($level-$target_level>0)
            $levels_relative[$level-$target_level] = $name;

    $_GET = array_merge((array) $levels_relative, $_GET);
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


function maxsim_autoload(array $ls, $load_prefix=false) {

    $prefix = [
        'first' => '*',
        'after' => '|',
        ];

    foreach ($ls AS $e)
        if (fnmatch("*.*", $e))
            foreach ($prefix AS $key => $value)
                if (substr(basename($e),0,1)==$prefix[$key] OR $load_prefix==$value)
                    $route[$key][] = $e;

    foreach ($ls AS $e)
        if (!fnmatch("*.*", $e))
            if (in_array(substr(basename($e),0,1), $prefix))
                if ($ls_recursive = glob(str_replace('*','\*',$e).'/*'))
                    $route = array_merge_recursive((array)$route, maxsim_autoload($ls_recursive, substr(basename($e),0,1)));

    return (array) $route;
}


function maxsim_config(array $config_input=[], string $config_file='$maxsim.json') {

    if (file_exists($config_file))
        $config = (array)json_decode(file_get_contents($config_file), true);

    if (count($config_input)>0) {
        $config = array_merge((array)$config, $config_input);
        $config = array_filter($config, static function($a){ return $a!==null; });
        file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));
    }

    return (array) $config;
}


function maxsim_absolute(string $dir) {
    return (string) str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $dir).'/';
}


function maxsim_include(string $file) {
    global $maxsim;

    if (fnmatch("*.php", $file))
        @include($file);

    else if (fnmatch("*.js", $file))
        $maxsim['template']['lib']['js'][] = $file;

    else if (fnmatch("*.css", $file))
        $maxsim['template']['lib']['css'][] = $file;
}