<?php maxsim_framework: # SIMPLICITY IS THE MAXIMUM SOPHISTICATION

$maxsim = [
    'maxsim_framework' => '0.7.0',
    'app'              => null,
    'app_dir'          => null,
    'app_url'          => null,
    'autoload'         => [],
];

chdir($_SERVER['DOCUMENT_ROOT']);
register_shutdown_function('maxsim_event', 'maxsim_exit');
maxsim_router();
ob_start();

maxsim_event('router');

// autoload
for ($maxsim_i = 0; $maxsim_i < count((array) $maxsim['autoload']); $maxsim_i++) {
    $maxsim_file = $maxsim['autoload'][$maxsim_i];

    if (str_ends_with($maxsim_file, '.php'))
        include_once($maxsim_file);
}

if ($maxsim['app_url'] === '/+maxsim/maxsim') {
    if (isset($_GET[1]) AND $_GET[1] === 'cron' AND isset($_GET[2])) {
        maxsim_event('maxsim_cron_'.$_GET[2]);
        exit;
    }
}

maxsim_event('users');

maxsim_event('header');

include($maxsim['app']);

maxsim_event('footer');


if (($maxsim['app'] === 'index.php' AND isset($_GET[1])) OR ob_get_length() === 0) {
    http_response_code(404);
    if (maxsim_event('error_404') === [])
        echo 'Error 404: NOT FOUND';
}

if (isset($template['title']))
    maxsim_event('template');

exit;



function maxsim_router() {
    global $maxsim;

    $levels = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

    if (isset($maxsim['first_dir']) AND count($levels) !== 2 AND !is_dir($levels[1]) AND !is_file($levels[1].'.php')) {
        $first_dir = $levels[1];
        unset($levels[1]);
        $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], (strlen($first_dir)+1));
        $_GET[$maxsim['first_dir']] = $first_dir;
    }

    $maxsim['autoload'] = [];
    foreach ($levels AS $id => $level) {
        $path[] = $level;

        if (!$ls = maxsim_scandir(($id !== 0?implode('/', array_filter($path)):'')))
            break;

        maxsim_autoload($ls);

        foreach ($ls AS $file)
            if (basename($file) === 'index.php')
                $maxsim['app'] = $file;

        foreach ($ls AS $file)
            if (isset($levels[$id + 1]) AND basename($file) === $levels[$id + 1].'.php')
                $maxsim['app'] = $file;
    }
    
    if ($maxsim['app'] !== false) {
        $maxsim['app_dir'] = (dirname($maxsim['app']) !== '.'?dirname($maxsim['app']).'/':'');
        $maxsim['app_url'] = '/'.str_replace(['/index','index'], '', substr($maxsim['app'],0,-4));
    }


    // get
    $app_level = count(explode('/', $maxsim['app'])) - 1;
    
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    
    if (substr($maxsim['app'],-9) === 'index.php')
        $url = '/index'.$url;

    $id = 0;
    foreach (array_filter(explode('/', $url)) AS $level => $value)
        if (($level - $app_level) > 0)
            $_GET[$id++] = $value;


    if (isset($first_dir)) {
        $_SERVER['REQUEST_URI'] = '/'.$first_dir.$_SERVER['REQUEST_URI'];
        $maxsim['app_url']      = '/'.$first_dir.$maxsim['app_url'];
    }
}


function maxsim_autoload(array $ls, bool $autoload_files = false) {
    global $maxsim;

    foreach ($ls AS $file)
        if (preg_match('/\.(php|js|css|ini)$/', basename($file)) === 1 AND basename($file) !== 'maxsim.php')
            if (!isset($maxsim['autoload']) OR !in_array($file, (array)$maxsim['autoload']))
                if (substr(basename($file),0,1) !== '!')
                    if ($autoload_files === true OR substr(basename($file),0,1) === '+')
                        $maxsim['autoload'][] = $file;

    foreach ($ls AS $dir) {
        $dir_curent = basename($dir);
        if (strpos($dir_curent, '.') !== false)
            continue;

        $prefix = substr($dir_curent,0,1);
        if ($prefix === '+')
            maxsim_autoload(maxsim_scandir($dir), true);
        else if ($prefix === '#' OR $prefix === '@')
            $maxsim['tags'][$prefix][substr($dir_curent,1)] = null;
    }
}


function maxsim_scandir(string $dir = '') {
    if ($dir !== '') {
        if (substr($dir, -1) !== '/')
            $dir .= '/';
        if (!is_dir($dir))
            return false;
    }

    $ls = scandir('./'.$dir);
    if (!is_array($ls))
        return (bool) false;

    $output = [];
    foreach ($ls AS $file)
        if (substr($file, 0, 1) !== '.')
            $output[] = $dir.$file;

    return (array) $output;
}


function maxsim_event(string $name) {
    global $maxsim, $template, $user;

    if (!isset($maxsim['events'])) {
        $maxsim['events'] = glob('{,*/,*/*/,*/*/*/}\!*.php', GLOB_BRACE); // 3 dir depth only.
        sort($maxsim['events']);
    }
    
    if ($name === 'maxsim_exit')
        chdir($_SERVER['DOCUMENT_ROOT']);
    
    $maxsim_event_output = [];
    foreach ($maxsim['events'] AS $file)
        if (preg_match('/^\!'.$name.'(\.|-)/', basename($file)) === 1)
            if ($maxsim_event_output[] = $file)
                include($file);

    return (array) $maxsim_event_output;
}