<?php

global $template;

$template['js'][] = '
document.querySelector("body").addEventListener("keydown", function(e) { 
    if (e.keyCode == 27) { 
        maxsim_ide_dir = "/'.maxsim_dir(__DIR__).'";
        maxsim_ide_target = "'.$maxsim['app'].'";
        import(maxsim_ide_dir + "ide.js"); 
        e.preventDefault(); 
    } 
});
';