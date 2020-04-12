<?php



echo 'Lab!';



__(maxsim\router($_SERVER['REQUEST_URI']));

__($_GET);

__(router());

__($maxsim);

echo html\table([['abc'=>'true'],['abc'=>'false']]);


function router() {
    return 'OK';
}


