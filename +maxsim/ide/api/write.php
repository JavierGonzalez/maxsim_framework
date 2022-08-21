<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <javier.gonzalez@maxsim.tech>


if (isset($_GET['file']) AND isset($_POST['code'])) {
    
    file_put_contents($_GET['file'], $_POST['code']);
    
    if (fnmatch("*.ts", $_GET['file']))
        shell("tsc ".escapeshellarg($_GET['file']));
}


exit;