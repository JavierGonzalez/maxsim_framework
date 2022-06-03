<?php maxsim: /* SIMPLICITY IS THE MAXIMUM SOPHISTICATION *\

MIT License

Copyright (c) 2005 Javier González González — maxsim.tech

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.                                                                  
                                                                                */

$maxsim = [
    'maxsim_version' => '0.5.20',
    'app' => false,
    'app_dir' => false,
    'app_url' => false,
    'autoload' => [],
];

ob_start();

foreach (maxsim_event('router') AS $file) include($file);
maxsim_router();


foreach (maxsim_event('autoload') AS $file) include($file);
for ($maxsim_al = 0; $maxsim_al < count((array)$maxsim['autoload']); $maxsim_al++) {
    $maxsim_file = $maxsim['autoload'][$maxsim_al];

    if (substr($maxsim_file,-4) === '.php')
        include_once($maxsim_file);

    else if (substr($maxsim_file,-4) === '.ini')
        if ($maxsim_key = ltrim(basename($maxsim_file, substr($maxsim_file,-4)), '+'))
            define($maxsim_key, (array)parse_ini_file($maxsim_file, true, INI_SCANNER_TYPED));
}
foreach (maxsim_event('autoload_after') AS $file) include($file);


foreach (maxsim_event('app') AS $file) include($file);
if (is_string($maxsim['app'])) include($maxsim['app']); ###
foreach (maxsim_event('app_after') AS $file) include($file);


if (isset($maxsim['redirect'])) {
    $_SERVER['REQUEST_URI'] = $maxsim['redirect'];
    unset($maxsim['redirect']);
    goto maxsim;
}

foreach (maxsim_event('template') AS $file) include($file);

foreach (maxsim_event('exit') AS $file) include($file);

exit;



function maxsim_router() {
    global $maxsim;

    $levels = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

    $maxsim['autoload'] = [];
    foreach ($levels AS $id => $level) {
        $path[] = $level;

        if (!$ls = maxsim_scandir(($id!==0?implode('/', array_filter($path)):'')))
            break;

        maxsim_autoload($ls);

        foreach ($ls AS $file)
            if (basename($file) === 'index.php')
                $maxsim['app'] = $file;

        foreach ($ls AS $file)
            if (isset($levels[$id + 1]) AND basename($file) === $levels[$id+1].'.php')
                $maxsim['app'] = $file;
    }
    
    if ($maxsim['app'] !== false) {
        $maxsim['app_dir'] = (dirname($maxsim['app'])!=='.'?dirname($maxsim['app']).'/':'');
        $maxsim['app_url'] = '/'.str_replace(['/index','index'], '', substr($maxsim['app'],0,-4));
    }

    if ($maxsim['app'] === '$maxsim.php') exit(json_encode(['maxsim_version' => $maxsim['maxsim_version']]));

    maxsim_get();
}


function maxsim_autoload(array $ls, bool $autoload_files = false) {
    global $maxsim;

    foreach ($ls AS $file)
        if (preg_match('/\.(php|js|css|ini)$/', basename($file)))
            if (!isset($maxsim['autoload']) OR !in_array($file, (array)$maxsim['autoload']))
                if (($autoload_files === true OR substr(basename($file),0,1) === '+') AND substr(basename($file),0,1) !== '!')
                    $maxsim['autoload'][] = $file;

    foreach ($ls AS $dir) {
        $dir_curent = basename($dir);
        if (strpos($dir_curent, '.') !== false)
            continue;

        $prefix = substr($dir_curent,0,1);
        if ($prefix === '+')
            maxsim_autoload(maxsim_scandir($dir), true);
        else if ($prefix === '#' OR $prefix === '@')
            $maxsim[$prefix][substr($dir_curent,1)] = null;
    }
}


function maxsim_get() {
    global $_GET, $maxsim;

    $app_level = count(explode('/', $maxsim['app']))-1;
    
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    
    if (substr($maxsim['app'],-9) === 'index.php')
        $url = '/index'.$url;

    $id = 0;
    foreach (array_filter(explode('/', $url)) AS $level => $value)
        if ($level-$app_level > 0)
            $_GET[$id++] = $value;
}


function maxsim_dir(string $dir = __DIR__) {
    return (string) substr(str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir).'/', 1);
}


function maxsim_scandir(string $dir = '') {
    global $maxsim;
    
    if ($dir !== '') {
        if (substr($dir, -1) !== '/')
            $dir .= '/';
        if (!is_dir($dir))
            return false;
    }

    foreach (maxsim_event('maxsim_ls') AS $file) include($file);
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
    global $maxsim;
    
    if (!isset($maxsim['events'])) {
        $maxsim['events'] = glob('{,*/,*/*/}!event_*.php', GLOB_BRACE);
        sort($maxsim['events']);
        foreach (maxsim_event('maxsim_ls') AS $file) include($file);
    }
    
    $output = [];
    foreach ($maxsim['events'] AS $file)
        if (fnmatch('!event_'.$name.'[.-]*', basename($file)))
            $output[] = $file;

    return (array) $output;
}
