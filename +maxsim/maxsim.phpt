<?

// Test execution in /maxsim/tests


# maxsim
is_readable('.')
is_readable('+maxsim/maxsim.php')
is_array($GLOBALS['maxsim'])
basename($_SERVER['DOCUMENT_ROOT']) === basename(getcwd())


# $_GET
'exec' === $_GET[0]
null   === $_GET[1]
2 === count($_GET)
'maxsim.phpt' === basename($_GET['file'])


# STATUS 200
200 === test_url($GLOBALS['maxsim']['app_url'], 'status')
200 === test_url($GLOBALS['maxsim']['app_url'].'/+.css', 'status')
200 === test_url($GLOBALS['maxsim']['app_url'].'/a?b=c', 'status')


# STATUS 404
404 === test_url('/not_exist', 'status')
404 === test_url('/not_exist.txt', 'status')
404 === test_url('/not_exist/not_exist.txt', 'status')
404 === test_url('/+', 'status')
404 === test_url('/%24', 'status')
404 === test_url('/+/', 'status')
404 === test_url('/+/a', 'status')
404 === test_url('/index.html', 'status')


# STATUS 403
403 === test_url('/index.php', 'status')
403 === test_url('/index.php/a', 'status')
403 === test_url('/index.php?a=b', 'status')

403 === test_url('/password', 'status')
403 === test_url('/+passwords.ini', 'status')
403 === test_url('/password.txt', 'status')
403 === test_url('/+maxsim/maxsim.phpt', 'status')
403 === test_url('/+maxsim/tests/tests.phpt', 'status')
403 === test_url('/+.php', 'status')

403 === test_url('/.a', 'status')
403 === test_url('/..b', 'status')

403 === test_url('/.git', 'status')
403 === test_url('/.git/index', 'status')
403 === test_url('/.git/logs/HEAD', 'status')

403 === test_url('/docker-compose.yml', 'status')
403 === test_url('/file.phpt', 'status')
403 === test_url('/file.log', 'status')
403 === test_url('/file.ini', 'status')
403 === test_url('/file.env', 'status')
403 === test_url('/file.yml', 'status')
403 === test_url('/file.md', 'status')
403 === test_url('/.htaccess', 'status')
403 === test_url('/.htpasswd', 'status')
403 === test_url('/.gitignore', 'status')
403 === test_url($GLOBALS['maxsim']['app_url'].'/.gitignore', 'status')


# File system routing test file
$test_app = 'test_'.mt_rand(10000000,99999999)
404  === test_url('/'.$test_app, 'status')
is_writable('.')
file_put_contents($test_app.'.php', '<?php exit(\'ok\');')
200  === test_url('/'.$test_app, 'status')
'ok' === test_url('/'.$test_app)
'ok' === test_url('/'.$test_app.'?a=b')
'ok' === test_url('/'.$test_app.'/a?b=c')
'ok' === test_url('/'.$test_app.'/a/b?c=d')
'ok' === test_url('/'.$test_app.'/a/b/?c=d')
unlink($test_app.'.php')


# File system routing test dir
$test_app_dir = 'dir_'.mt_rand(10000000,99999999)
404  === test_url('/'.$test_app_dir, 'status')
is_writable('.')
mkdir($test_app_dir)
file_put_contents($test_app_dir.'/index.php', '<?php exit(\'ok\');')
200  === test_url('/'.$test_app_dir.'/a/b/?c=d', 'status')
200  === test_url('/'.$test_app_dir, 'status')
'ok' === test_url('/'.$test_app_dir)
'ok' === test_url('/'.$test_app_dir.'?a=b')
'ok' === test_url('/'.$test_app_dir.'/a?b=c')
'ok' === test_url('/'.$test_app_dir.'/a/b?c=d')
'ok' === test_url('/'.$test_app_dir.'/a/b/?c=d')
unlink($test_app_dir.'/index.php')
rmdir($test_app_dir)


# maxsim_dir()
'exec.php' === basename($GLOBALS['maxsim']['app'])
'+maxsim/tests/' === $GLOBALS['maxsim']['app_dir']
'/+maxsim/tests/exec' === $GLOBALS['maxsim']['app_url']
'+maxsim/tests/' === maxsim_dir(__DIR__)
true === in_array($GLOBALS['maxsim']['app_dir'].'+.php', $GLOBALS['maxsim']['autoload'])


# maxsim_scandir()
glob('*') === maxsim_scandir('')
glob('*')[1] === maxsim_scandir('')[1]
3 <= count(maxsim_scandir())
'+maxsim/tests/+.css' === maxsim_scandir('+maxsim/tests')[0]
'+maxsim/tests/+.css' === maxsim_scandir('+maxsim/tests/')[0]
2 === count(glob('+maxsim/{maxsim.php,maxsim.phpt}', GLOB_BRACE))
'+maxsim/maxsim.phpt' === glob('+maxsim/{maxsim.php,maxsim.phpt}', GLOB_BRACE)[1]


# maxsim_event()
in_array('+maxsim/+debug/!maxsim_autoload.php',       maxsim_event('maxsim_autoload'))
in_array('+maxsim/+debug/!maxsim_autoload_after.php', maxsim_event('maxsim_autoload_after'))
