<?php # maxsim

function maxsim_template() {
    global $maxsim, $echo;

    if (is_array($echo))
        include('api/json.php');
    else 
        include('html/index.php');

}

register_shutdown_function('maxsim_template');
