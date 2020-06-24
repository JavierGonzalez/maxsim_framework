<?php # maxsim.tech


$maxsim['template']['js_array']['ide_dir'] = '/'.maxsim_absolute(__DIR__);
$maxsim['template']['js_array']['ide_target'] = $maxsim['route']['app'][0];

$maxsim['template']['js'] .= '

document.querySelector("body").addEventListener("keydown", function(e) {
    if(e.keyCode == 27 && typeof ace == "undefined") {
        import(ide_dir + "ide.js");
        e.preventDefault();
    }
});

';
