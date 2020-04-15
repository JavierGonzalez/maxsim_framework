

$.getScript(ide_relative_dir + "ace/ace.js", function() {
    $.get(ide_relative_dir + "api/file?file=" + maxsim_target, function(data) {
        $("body").prepend("<div id=\"ide_editor\"></div>");
        $("#ide_editor").text(data);
        var editor = ace.edit("ide_editor");
        ace.config.set('basePath', '/' + ide_relative_dir + 'ace');
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/php");
    });
});

$("<link/>", {
   rel: "stylesheet",
   type: "text/css",
   href: ide_relative_dir + "ide.css"
}).appendTo("head");