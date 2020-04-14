<?php


__();
__(maxsim_router($_SERVER['REQUEST_URI']));

__($_GET);

__(router());


echo html\table([['abc'=>'true'],['abc'=>'false']]);


function router() {
    return 'OK';
}


