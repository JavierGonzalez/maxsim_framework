<?

// maxsim iftest
// Execution in: /iftest


# maxsim
is_readable('.')
is_readable('+maxsim/maxsim.php')
is_array($GLOBALS['maxsim'])
basename($_SERVER['DOCUMENT_ROOT']) === basename(getcwd())


# $_GET
'exec' === $_GET[0]
null   === $_GET[1]
2 === count($_GET)


# net
$_SERVER['HTTP_HOST']
$_SERVER['SERVER_ADDR']
$_SERVER['SERVER_NAME']


# STATUS 200
200 === iftest_url('/', 'status')
200 === iftest_url('/', 'status')


# STATUS 404
404 === iftest_url('/not_exist', 'status')
404 === iftest_url('/not_exist.txt', 'status')
404 === iftest_url('/not_exist/not_exist.txt', 'status')
404 === iftest_url('/+', 'status')
404 === iftest_url('/%24', 'status')
404 === iftest_url('/+/', 'status')
404 === iftest_url('/+/a', 'status')
404 === iftest_url('/index.html', 'status')
404 === iftest_url('/dockerfile', 'status')


# STATUS 403
403 === iftest_url('/index.php', 'status')
403 === iftest_url('/index.php/a', 'status')
403 === iftest_url('/index.php?a=b', 'status')

403 === iftest_url('/password', 'status')
403 === iftest_url('/+passwords.ini', 'status')
403 === iftest_url('/password.txt', 'status')
403 === iftest_url('/+maxsim/maxsim.phpt', 'status')
403 === iftest_url('/+.php', 'status')

403 === iftest_url('/.a', 'status')
403 === iftest_url('/..b', 'status')

403 === iftest_url('/.git', 'status')
403 === iftest_url('/.git/index', 'status')
403 === iftest_url('/.git/logs/HEAD', 'status')

403 === iftest_url('/docker-compose.yml', 'status')
403 === iftest_url('/Dockerfile', 'status')
403 === iftest_url('/file.phpt', 'status')
403 === iftest_url('/file.log', 'status')
403 === iftest_url('/file.ini', 'status')
403 === iftest_url('/file.env', 'status')
403 === iftest_url('/file.yml', 'status')
403 === iftest_url('/file.md', 'status')
403 === iftest_url('/.htaccess', 'status')
403 === iftest_url('/.htpasswd', 'status')
403 === iftest_url('/.gitignore', 'status')
403 === iftest_url($GLOBALS['maxsim']['app_url'].'/.gitignore', 'status')


# File system routing test file
$test_app = 'maxsim_test_test_'.mt_rand(10000000,99999999)
404  === iftest_url('/'.$test_app, 'status')
is_writable('.')
file_put_contents($test_app.'.php', '<?php exit(\'ok\');')
200  === iftest_url('/'.$test_app, 'status')
'ok' === iftest_url('/'.$test_app)
'ok' === iftest_url('/'.$test_app.'?a=b')
'ok' === iftest_url('/'.$test_app.'/a?b=c')
'ok' === iftest_url('/'.$test_app.'/a/b?c=d')
'ok' === iftest_url('/'.$test_app.'/a/b/?c=d')
unlink($test_app.'.php')


# File system routing test dir
$test_app_dir = 'maxsim_test_dir_'.mt_rand(10000000,99999999)
false === iftest_url('/'.$test_app_dir)
404  === iftest_url('/'.$test_app_dir, 'status')
is_writable('.')
mkdir($test_app_dir)
file_put_contents($test_app_dir.'/index.php', '<?php exit(\'ok\');')
200  === iftest_url('/'.$test_app_dir.'/a/b/?c=d', 'status')
200  === iftest_url('/'.$test_app_dir, 'status')
'ok' === iftest_url('/'.$test_app_dir)
'ok' === iftest_url('/'.$test_app_dir.'?a=b')
'ok' === iftest_url('/'.$test_app_dir.'/a?b=c')
'ok' === iftest_url('/'.$test_app_dir.'/a/b?c=d')
'ok' === iftest_url('/'.$test_app_dir.'/a/b/?c=d')
unlink($test_app_dir.'/index.php')
rmdir($test_app_dir)


# maxsim_dir()
maxsim_dir(__DIR__)
'exec.php' === basename($GLOBALS['maxsim']['app'])
true === in_array($GLOBALS['maxsim']['app_dir'].'+.php', $GLOBALS['maxsim']['autoload'])


# maxsim_scandir()
glob('+maxsim/*') === maxsim_scandir('+maxsim')
glob('+maxsim/*')[1] === maxsim_scandir('+maxsim')[1]
3 <= count(maxsim_scandir())
'+maxsim/+debug' === maxsim_scandir('+maxsim')[0]
'+maxsim/+debug' === maxsim_scandir('+maxsim/')[0]
2 === count(glob('+maxsim/{maxsim.php,maxsim.phpt}', GLOB_BRACE))
'+maxsim/maxsim.phpt' === glob('+maxsim/{maxsim.php,maxsim.phpt}', GLOB_BRACE)[1]


# maxsim_event()
// in_array('+maxsim/+debug/!maxsim_autoload.php',       maxsim_event('maxsim_autoload'))
// in_array('+maxsim/+debug/!maxsim_autoload_after.php', maxsim_event('maxsim_autoload_after'))
