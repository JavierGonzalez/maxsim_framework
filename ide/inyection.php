<?php

$maxsim['template']['js'] .= '

function KeyPress(e) {
    var evtobj = window.event? event : e
    if (evtobj.keyCode == 77 && evtobj.ctrlKey) {
        alert("Ctrl+z");
    }
}

document.onkeydown = KeyPress;

';