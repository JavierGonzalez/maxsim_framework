<?php # maximum_simplicity

?>


<style type="text/css" media="screen">
    #editor { 
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>


<div id="editor"><?=htmlspecialchars(file_get_contents('$maxsim.php'))?></div>
    
<script src="ace-1.4.9/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/php");
</script>
