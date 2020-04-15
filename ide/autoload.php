<?php # maxsim.tech

$maxsim['template']['js_array']['ide_relative_dir'] = maxsim_relative(__DIR__);
$maxsim['template']['js_array']['maxsim_target'] = $maxsim['target'];

$maxsim['template']['js'] .= '

$("body").on("keyup keydown", function(e) {
    if(e.keyCode == 27 || (e.ctrlKey && e.keyCode == 83)) {
        $.getScript(ide_relative_dir + "ide.js");
        e.preventDefault();
    }
});

';