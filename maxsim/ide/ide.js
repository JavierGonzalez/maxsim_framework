// maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


// JS
import "./vendor/jquery/jquery-3.5.0.min.js";
import "./vendor/jquery/jquery-ui.min.js";
import "./vendor/lightweight-charts.standalone.production.js";

$('head').append('<script>var require = { paths: { "vs": "/maxsim/ide/vendor/monaco-editor/vs" } };</script>');
$('head').append('<script src="/maxsim/ide/vendor/monaco-editor/vs/loader.js"></script>');


// CSS
$('head').append('<link rel="stylesheet" type="text/css" href="' + maxsim_ide_dir + 'ide.css">');
$('head').append('<link rel="stylesheet" type="text/css" href="' + maxsim_ide_dir + 'vendor/jquery/jquery-ui.min.css">');


// HTML
$("body").append('<div id="maxsim_ide" style="display:none;"><div id="maxsim_ide_graph"></div><div id="maxsim_ide_menu"></div><div id="maxsim_ide_tree"></div><div id="maxsim_ide_editor"></div></div>');
$("#maxsim_ide_menu").html('<span><a href="https://maxsim.tech" target="_blank">maxsim</a></span>');



var maxsim_monaco_editor = null;

maxsim_ide_load_file();



// Events

$("body").on("keydown", function(e) {
    if (e.keyCode == 27) {
        $("#maxsim_ide").toggle();
        maxsim_monaco_editor.focus();
        e.preventDefault();
    } else if (e.keyCode == 83 && e.ctrlKey && $("#maxsim_ide").is(":visible") == true) {
        $("#maxsim_ide").toggle();
        $.post(maxsim_ide_dir + "api/write?file=" + encodeURIComponent(maxsim_ide_target), { code: maxsim_monaco_editor.getValue() }, function(data){
            location.reload();
        });
        e.preventDefault();
    }
});




// Functions


function maxsim_ide_load_file() {
    $.get(maxsim_ide_dir + "api/read?file=" + encodeURIComponent(maxsim_ide_target), function(data){
        maxsim_ide_editor(data);
        maxsim_ide_graph();
    });
}

function maxsim_ide_editor(data) {

    require(['vs/editor/editor.main'], function () {
            
        if (!maxsim_monaco_editor) {
            maxsim_monaco_editor = monaco.editor.create(document.getElementById('maxsim_ide_editor'), {
                model: null,
                minimap: {
                    renderCharacters: false,
                    showSlider: 'always',
                },
            });
        }
        
        var file_extension = maxsim_ide_target.split('.').pop();
        var extension_map = {
                php:    'php',
                js:     'javascript',
                html:   'html',
                css:    'css',
                ts:     'typescript',
                txt:    'text',
                md:     'markdown',
                ini:    'ini',
                dokerfile: 'dokerfile',
                xml:     'xml',
                yaml:    'yaml',
                sql:     'sql',
            };
        
        if (typeof extension_map[file_extension] !== 'undefined')
            var file_extension = extension_map[file_extension];
        else
            var file_extension = 'text';


        var newModel = monaco.editor.createModel(data, file_extension);
        maxsim_monaco_editor.setModel(newModel);
        monaco.editor.setTheme('vs-dark');

        maxsim_monaco_editor.focus();
    });
    
    $("#maxsim_ide").on('resize', function(){
        maxsim_monaco_editor.layout();
    });

    $("#maxsim_ide").resizable({ 
        handles: "w", 
        minWidth: 80, 
        maxWidth: $(window).width(),
        alsoResize: '#maxsim_ide_editor',
    });
    $(".ui-resizable-handle.ui-resizable-w").css("width", 20).css("height", "90%");

    maxsim_ide_tree(maxsim_ide_target);
    
    $('#maxsim_ide').show();
}


function maxsim_ide_tree(item) {
    
    var dir_last = ' ';

    $.get(maxsim_ide_dir + "api/list?target=" + encodeURIComponent(item), function(data){
        
        $("#maxsim_ide_tree").html('');

        data['tree'].forEach(function(i,k,a) {
            
            if (i['type'] == 'dir') {
                var icon = 'default_folder.svg';
            } else if (i['logs']) {
                var icon = 'file_type_favicon.svg';
            } else {
                var icon = 'empty.svg';
            }

            var name = i['name'];

            var tab_num = (name.split('/').length - 2);
            if (i['type'] == 'file')
                tab_num++;
            var tab = '&nbsp;&nbsp;&nbsp; '.repeat(tab_num);

            if (i['type'] == 'dir') {
                var name = name.substr(0, name.length - 1);
                
                if (name.substr(0, dir_last.length) == dir_last) {
                    var name = name.substr(dir_last.length);
                    $('.maxsim_ide_tree_item[data-item="' + dir_last + '"] img').attr('src', '/maxsim/ide/vendor/icons/default_folder_opened.svg');
                } else {
                    dir_last = name;
                }
            } else {
                var name = name.split('/').reverse()[0];
            }

            $("#maxsim_ide_tree").append('<div class="maxsim_ide_tree_item" data-item="' + i['name'] + '" data-type="' + i['type'] + '">' + tab + '<img src="/maxsim/ide/vendor/icons/' + icon + '" width="16" height="16" /> ' + name + '</div>');
        });

    
        $('.maxsim_ide_tree_item[data-item="' + maxsim_ide_target + '"]').addClass('maxsim_ide_tree_item_selected');
    

        // Event onclick
        $(".maxsim_ide_tree_item").click(function() {
            var item_this = $(this).attr('data-item');

            if ($(this).attr('data-type') == 'file') {
                maxsim_ide_target = item_this;
                $(".maxsim_ide_tree_item").removeClass('maxsim_ide_tree_item_selected');
                $(this).addClass('maxsim_ide_tree_item_selected');
        
                maxsim_ide_load_file();
            } else {
                maxsim_ide_tree(item_this);
            }
        });

    });

}


function maxsim_ide_graph() {


    $.get(maxsim_ide_dir + "api/graph?file=" + encodeURIComponent(maxsim_ide_target), function(data){

        $("#maxsim_ide_graph").html('');

        const chart = LightweightCharts.createChart(document.getElementById('maxsim_ide_graph'), { });

        chart.applyOptions({
            layout: {
                backgroundColor: '#ffffff00',
                textColor: '#696969',
                fontSize: 12,
                fontFamily: 'Calibri',
            },
            grid: {
                vertLines: {
                    visible: false,
                },
                horzLines: {
                    visible: false,
                },
            },
            timeScale: {
                visible: true,
                rightOffset: 10,
                borderVisible: false,
                secondsVisible: true,
            },    
            crosshair: {
                vertLine: {
                    color: '#6A5ACD',
                    width: 0.5,
                    style: 1,
                    visible: true,
                    labelVisible: true,
                },
                horzLine: {
                    visible: false,
                    labelVisible: false,
                },
                mode: 0,
            },    
            priceScale: {
                mode: 1,
                drawTicks: false,
            }
        });

        var graph_data_color = ['yellow', 'green', 'blue', 'purple', 'brown', 'orange', 'red', 'black'];
        var n = 0;
        for(var k in data[0]) {

            if (k == 'time' || k == 'url')
                continue;
            
            var data_set = [];

            for(var l in data)
                data_set.push({ time: data[l]['time'], value: data[l][k] });

            chart.addLineSeries({
                title: k,
                priceLineVisible: false,
                color: graph_data_color[n],
            }).setData(data_set);
        
            n++;
        }
    });
}

