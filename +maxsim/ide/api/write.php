<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


if ($_GET['file'] AND isset($_POST['code'])) {
    
    file_put_contents($_GET['file'], $_POST['code']);
    
    if (fnmatch("*.ts", $_GET['file']))
        shell("tsc ".escapeshellarg($_GET['file']));
}


exit;