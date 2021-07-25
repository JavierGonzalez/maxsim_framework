// maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>



import "./lib/jquery/jquery-3.5.0.min.js";
import "./lib/jquery/jquery-ui.min.js";
import "./lib/ace/ace.js";



$("body").append('<div id="maxsim_ide"><div id="maxsim_ide_menu"></div><div id="maxsim_ide_tree"></div><div id="maxsim_ide_editor"></div></div>');

$('head').append('<link rel="stylesheet" type="text/css" href="' + maxsim_ide_dir + 'ide.css">');
$('head').append('<link rel="stylesheet" type="text/css" href="' + maxsim_ide_dir + 'lib/jquery/jquery-ui.min.css">');


var maxsim_ide_editor = '';

$.get(maxsim_ide_dir + "api/read?file=" + encodeURIComponent(maxsim_ide_target), function(data){
    
    $("#maxsim_ide_menu").html('<span><a href="https://maxsim.tech">maxsim</a></span>');
    $("#maxsim_ide_editor").text(data);
    
    maxsim_ide_editor = ace.edit("maxsim_ide_editor");
    ace.config.set("basePath", maxsim_ide_dir + "lib/ace");
    maxsim_ide_editor.setTheme("ace/theme/monokai");
    maxsim_ide_editor.session.setMode("ace/mode/php");
    maxsim_ide_editor.focus();
    maxsim_ide_editor.navigateFileEnd();
    maxsim_ide_editor.setShowPrintMargin(false);
    maxsim_ide_editor.setOption("fixedWidthGutter", true);
    maxsim_ide_editor.setOption("foldStyle", "manual");
    
    $("#maxsim_ide").resizable({ 
        handles: "w", 
        minWidth: 80, 
        maxWidth: $(window).width()
    });
    $(".ui-resizable-handle.ui-resizable-w").css("width", 20).css("height", "90%");
});



$.get(maxsim_ide_dir + "api/list?target=" + encodeURIComponent(maxsim_ide_target), function(data){
    data.forEach(function(item) {
        $("#maxsim_ide_tree").append('<div class="maxsim_ide_tree_item" data-item="' + item + '">' + item + '</div>');
    });

    $(".maxsim_ide_tree_item").click(function() {
        maxsim_ide_target = $(this).attr('data-item');
        $(".maxsim_ide_tree_item").removeClass('maxsim_ide_tree_item_selected');
        $(this).addClass('maxsim_ide_tree_item_selected');
        $.get(maxsim_ide_dir + "api/read?file=" + encodeURIComponent(maxsim_ide_target), function(data){
            maxsim_ide_editor.setValue(data);
            maxsim_ide_editor.clearSelection();
            maxsim_ide_editor.focus();
        });
    });
});



$("body").on("keydown", function(e) {
    if (e.keyCode == 27) {
        $("#maxsim_ide").toggle();
        maxsim_ide_editor.focus();
        e.preventDefault();
    } else if (e.keyCode == 83 && e.ctrlKey && $("#maxsim_ide").is(":visible") == true) {
        $("#maxsim_ide").toggle();
        $.post(maxsim_ide_dir + "api/write?file=" + encodeURIComponent(maxsim_ide_target), { code: maxsim_ide_editor.getValue() }, function(data){
            location.reload();
        });
        e.preventDefault();
    }
});



