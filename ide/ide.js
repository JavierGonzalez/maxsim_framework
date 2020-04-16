// maxsim.tech

$.getScript(ide_relative_dir + "ace/ace.js", function(){
    $.get(ide_relative_dir + "api/read?file=" + maxsim_target, function(data){
        var ide_footer = '<a href="https://maxsim.tech" style="color:#FFF;text-decoration:none;">maxsim</a> &nbsp; &nbsp; &nbsp; Ctrl+S = save';
        $("body").prepend('<div id="ide"><div id="ide_footer">' + ide_footer + '</div><div id="ide_editor"></div></div>');
        $("#ide_editor").text(data);
        
        ide_editor = ace.edit("ide_editor");
        ace.config.set("basePath", "/" + ide_relative_dir + "ace");
        ide_editor.setTheme("ace/theme/monokai");
        ide_editor.session.setMode("ace/mode/php");
        ide_editor.focus();
        ide_editor.navigateFileEnd();

        $.getScript(ide_relative_dir + "jquery-ui.min.js", function(){
            $("#ide_editor").resizable({ handles: "w", minWidth: 80, maxWidth: $(window).width(), alsoResize: "#ide_footer" });
            $(".ui-resizable-handle.ui-resizable-w").css("width", 50);
        });
    });
});


$("<link/>", {
   rel: "stylesheet",
   type: "text/css",
   href: ide_relative_dir + "ide.css"
}).appendTo("head");

$("<link/>", {
   rel: "stylesheet",
   type: "text/css",
   href: ide_relative_dir + "jquery-ui.min.css"
}).appendTo("head");


$("body").on("keydown", function(e) {
    if (e.keyCode == 27) {
        $("#ide").toggle();
        ide_editor.focus();
        e.preventDefault();
    } else if ($("#ide").is(":visible") == true && e.ctrlKey && e.keyCode == 83) {
        $.post(ide_relative_dir + "api/write?file=" + maxsim_target, { code: ide_editor.getValue() }, function(data){
            
        });
        e.preventDefault();
    }
});


