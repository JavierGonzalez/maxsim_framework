<?php maxsim: /* SIMPLICITY IS THE MAXIMUM SOPHISTICATION

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
SOFTWARE.                                                                  */

$maxsim['version'] = '0.5.15';

ob_start();
maxsim_router();
maxsim_get();
$maxsim['debug']['timing']['router'] = microtime(true);


for ($maxsim_al = 0; $maxsim_al < count((array)$maxsim['autoload']); $maxsim_al++) {
    $maxsim_file = $maxsim['autoload'][$maxsim_al];
    $maxsim_ext = pathinfo($maxsim_file, PATHINFO_EXTENSION);

    if ($maxsim_ext === 'php')
        include_once($maxsim_file);

    else if ($maxsim_ext === 'ini')
        if ($key = ltrim(basename($maxsim_file, '.'.$maxsim_ext), '+'))
            define($key, (array)parse_ini_file($maxsim_file, true, INI_SCANNER_TYPED));
    
    else if ($maxsim_ext === 'json')
        if ($key = ltrim(basename($maxsim_file, '.'.$maxsim_ext), '+'))
            ${$key} = (array)json_decode(file_get_contents($maxsim_file), true);
}
$maxsim['debug']['timing']['autoload'] = microtime(true);


if (is_string($maxsim['app']))
    include_once($maxsim['app']);
$maxsim['debug']['timing']['app'] = microtime(true);


if (isset($maxsim['redirect'])) {
    $_SERVER['REQUEST_URI'] = $maxsim['redirect'];
    unset($maxsim['redirect']);
    goto maxsim;
}

if (!isset($maxsim['output']))
    $maxsim['output'] = 'template';

if ($maxsim['output'] === 'text') {
    header('content-type: text/plain');

} else if ($maxsim['output'] === 'json' AND is_array($echo)) {
    ob_end_clean();
    header('content-type: application/json');
    echo json_encode((array)$echo, JSON_PRETTY_PRINT);

} else if (file_exists($maxsim['output'].'/index.php')) {
    $echo = ob_get_contents();
    ob_end_clean();
    include($maxsim['output'].'/index.php');
}

exit;


function maxsim_router() {
    global $maxsim;
    $maxsim['app'] = false;

    $levels = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

    $maxsim['autoload'] = [];
    foreach ($levels AS $id => $level) {
        $path[] = $level;

        if (!$ls = glob(($id?implode('/', array_filter($path)).'/':'').'*'))
            break;

        maxsim_autoload($ls);

        foreach ($ls AS $file)
            if (in_array(basename($file), ['index.php', 'index.html']))
                $maxsim['app'] = $file;

        foreach ($ls AS $file)
            if (isset($levels[$id+1]) AND basename($file) === $levels[$id+1].'.php')
                $maxsim['app'] = $file;
    }
}


function maxsim_autoload(array $ls, bool $autoload_files=false) {
    global $maxsim;

    foreach ($ls AS $file)
        if (preg_match('/\.(php|js|css|ini|json)$/', basename($file)))
            if (!isset($maxsim['autoload']) OR !in_array($file, (array)$maxsim['autoload']))
                if ($autoload_files OR substr(basename($file),0,1) === '+')
                    $maxsim['autoload'][] = $file;

    foreach ($ls AS $dir)
        if (!fnmatch('*.*', basename($dir)))
            if (substr(basename($dir),0,1) === '+')
                maxsim_autoload(glob($dir.'/*'), true);
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