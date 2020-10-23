<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


if ($_GET[1]=='read' AND $_GET['file'])
    echo file_get_contents($_GET['file']);


if ($_GET[1]=='write' AND $_GET['file'] AND $_POST['code'])
    if (file_put_contents($_GET['file'], $_POST['code']))
        if (fnmatch("*.ts", $_GET['file']))
            shell("tsc ".escapeshellarg($_GET['file']));


exit;