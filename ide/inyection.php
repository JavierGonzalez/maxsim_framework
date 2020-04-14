<?php


$maxsim['template']['css'] .= '
#ide_editor { 
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 150px;
}
';

// $maxsim['template']['lib']['js'][] = maxsim_relative(__DIR__).'jquery-3.5.0.slim.min.js';

$maxsim['template']['js'] .= '

function KeyPress(e) {
    var evtobj = window.event? event : e
    if (evtobj.keyCode == 27 || (evtobj.keyCode == 83 && evtobj.ctrlKey)) {
        
        loadScript("'.maxsim_relative(__DIR__).'jquery-3.5.0.slim.min.js", function(){
            loadScript("'.maxsim_relative(__DIR__).'ace-1.4.9/ace.js", function(){

               $.get("'.maxsim_relative(__DIR__).'api/file?file='.$maxsim['target'].'", function( data ) {
                    $("body").prepend("<div id=\"ide_editor\"></div>");
                    var editor = ace.edit("ide_editor");
                    editor.setTheme("ace/theme/monokai");
                    editor.session.setMode("ace/mode/php");
               });
            });
        });

    }
    e.preventDefault();
}

document.onkeydown = KeyPress;


function loadScript(url, callback){

    var script = document.createElement("script")
    script.type = "text/javascript";

    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }

    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}


';
?>

