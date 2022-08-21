<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <javier.gonzalez@maxsim.tech>

ob_end_clean();

if (isset($_GET['file']))
    echo file_get_contents($_GET['file']);

exit;