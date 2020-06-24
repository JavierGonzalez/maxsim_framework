<?php # maxsim.tech — Copyright (c) 2020 Javier González González — ALL RIGHTS RESERVED


maxsim();
exit;


function maxsim() {
    global $maxsim;

    $maxsim = [
        'version' => '0.5.0',
        'debug'   => [ 'crono' => hrtime(true) ],
        ];

    error_reporting(E_ALL ^ E_NOTICE);

    $maxsim['route'] = maxsim_router($_SERVER['REQUEST_URI']);

    maxsim_get($_SERVER['REQUEST_URI']);

    ob_start();

    foreach ($maxsim['route'] AS $value)
        foreach ($value AS $file)
            maxsim_include($file);
}


function maxsim_get(string $uri) {
    global $maxsim, $_GET;

    $app_level = count(explode('/', $maxsim['route']['app'][0]))-1;

    $url = explode('?', $uri)[0];
    if ($url=='/')
        $url = '/index';

    $levels = array_filter(explode('/', $url));
    foreach ($levels AS $level => $name)
        if ($level-$app_level>0)
            $levels_relative[$level-$app_level] = $name;

    $_GET = array_merge((array) $levels_relative, $_GET);
}


function maxsim_router(string $uri) {

    $route = [
        'autoload' => [], // Include files or directories starting with *.
        'app'      => [], // Application code.
        ];

    $url = explode('?', $uri)[0];
    if ($url=='/')
        $url = '/index';

    $levels = explode('/', $url);

    foreach ($levels AS $id => $level) {
        $level_path[] = $level;

        if (!$ls = glob(($id===0?'*':implode('/',array_filter($level_path)).'/*'))) // Refact
            break;

        $route = array_merge_recursive($route, maxsim_autoload($ls));

        foreach ($ls AS $e)
            if (!$route['app'] AND basename($e)==$levels[$id+1].'.php')
                $route['app'][] = $e;
        
        foreach ($ls AS $e)
            if (!$route['app'] AND $id>0 AND basename($e)=='index.php')
                $route['app'][] = $e;

        if ($id===0 AND in_array('$maxsim.json', $ls))
            $route = array_merge_recursive($route, (array) maxsim_config()['autoload']);

        if (count($route['app'])>0)
            break;
    }


    if (!$route['app'] AND file_exists('404.php'))
        $route['app'][] = '404.php';

    return (array) $route;
}


function maxsim_autoload(array $ls, $load_prefix=false) {

    foreach ($ls AS $e)
        if (preg_match("/\.(php|js|css)$/", $e))
            if (substr(basename($e),0,1)=='*' OR $load_prefix=='*')
                $route['autoload'][] = $e;

    foreach ($ls AS $e)
        if (!fnmatch("*.*", $e))
            if (substr(basename($e),0,1)=='*')
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

    else if (fnmatch("*.css", $file))
        $maxsim['template']['autoload']['css'][] = $file;

    else if (fnmatch("*.js", $file))
        $maxsim['template']['autoload']['js'][] = $file;
}