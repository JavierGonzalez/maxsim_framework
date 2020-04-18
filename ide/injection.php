<?php # maxsim.tech


$maxsim['template']['js_array']['ide_dir'] = '/'.maxsim_absolute(__DIR__);
$maxsim['template']['js_array']['maxsim_target'] = $maxsim['target'];

$maxsim['template']['js'] .= '

$("body").on("keydown", function(e) {
    if(typeof ace == "undefined" && (e.keyCode == 27 || (e.ctrlKey && e.keyCode == 83))) {
        $.getScript(ide_dir + "ide.js");
        e.preventDefault();
    }
});
';
