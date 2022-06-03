<?php


$echo = ob_get_contents();
ob_end_clean();

include_once(maxsim_dir(__DIR__).'html.php');