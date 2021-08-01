<?php


?>


<script src="/maxsim/ide/vendor/jquery/jquery-3.5.0.min.js"></script>


<script>var require = { paths: { 'vs': '/maxsim/ide/vendor/monaco-editor/vs' } };</script>
<script src="/maxsim/ide/vendor/monaco-editor/vs/loader.js"></script>

<script>

// "use strict";

// import "/maxsim/ide/vendor/monaco-editor/vs/loader.js";


var maxsim_monaco_editor = null;

$(document).ready(function() {
	require(['vs/editor/editor.main'], function () {

        $.get('/maxsim/ide/api/read?file=maxsim%2Fide%2Fmonaco.php', function(data){
            
            if (!maxsim_monaco_editor) {
                $('#editor').empty();
                maxsim_monaco_editor = monaco.editor.create(document.getElementById('editor'), {
                    model: null,
                });
            }

            var newModel = monaco.editor.createModel(data, 'php');
            maxsim_monaco_editor.setModel(newModel);
            monaco.editor.setTheme('vs-dark');

        });


	});

});


</script>





<div id="editor" style="height:800px;"></div>