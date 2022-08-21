<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>

ob_end_clean();

if (isset($_GET['file']))
    echo file_get_contents($_GET['file']);

exit;