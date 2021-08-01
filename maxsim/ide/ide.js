// maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


// JS
import "./vendor/jquery/jquery-3.5.0.min.js";
import "./vendor/jquery/jquery-ui.min.js";
import "./vendor/ace/ace.js";
import "./vendor/lightweight-charts.standalone.production.js";

// CSS
$('head').append('<link rel="stylesheet" type="text/css" href="' + maxsim_ide_dir + 'ide.css">');
$('head').append('<link rel="stylesheet" type="text/css" href="' + maxsim_ide_dir + 'vendor/jquery/jquery-ui.min.css">');

// HTML
$("body").append('<div id="maxsim_ide"><div id="maxsim_ide_graph"></div><div id="maxsim_ide_menu"></div><div id="maxsim_ide_tree"></div><div id="maxsim_ide_editor"></div></div>');
$("#maxsim_ide_menu").html('<span><a href="https://maxsim.tech" target="_blank">maxsim</a></span>');



var maxsim_ide_ace = [];

maxsim_ide_load_file();



// Events

$("body").on("keydown", function(e) {
    if (e.keyCode == 27) {
        $("#maxsim_ide").toggle();
        maxsim_ide_ace.focus();
        e.preventDefault();
    } else if (e.keyCode == 83 && e.ctrlKey && $("#maxsim_ide").is(":visible") == true) {
        $("#maxsim_ide").toggle();
        $.post(maxsim_ide_dir + "api/write?file=" + encodeURIComponent(maxsim_ide_target), { code: maxsim_ide_ace.getValue() }, function(data){
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

    if (maxsim_ide_ace == '') {
        $("#maxsim_ide_editor").text(data);
        
        maxsim_ide_ace = ace.edit("maxsim_ide_editor");
        ace.config.set("basePath", maxsim_ide_dir + "vendor/ace");
        maxsim_ide_ace.setTheme("ace/theme/monokai");
        maxsim_ide_ace.session.setMode("ace/mode/php");
        maxsim_ide_ace.focus();
        maxsim_ide_ace.navigateFileEnd();
        maxsim_ide_ace.setShowPrintMargin(false);
        maxsim_ide_ace.setOption("fixedWidthGutter", true);
        maxsim_ide_ace.setOption("foldStyle", "manual");
        
        $("#maxsim_ide").resizable({ 
            handles: "w", 
            minWidth: 80, 
            maxWidth: $(window).width()
        });
        $(".ui-resizable-handle.ui-resizable-w").css("width", 20).css("height", "90%");
        

        maxsim_ide_tree(maxsim_ide_target);
        
    } else {
        maxsim_ide_ace.setValue(data);
        maxsim_ide_ace.clearSelection();
        maxsim_ide_ace.focus();
    }
    
}


function maxsim_ide_tree(item) {
    
    $("#maxsim_ide_tree").html('');

    $.get(maxsim_ide_dir + "api/list?target=" + encodeURIComponent(item), function(data){

        data['tree'].forEach(function(i) {
            
            if (i['type'] == 'dir') {
                var icon = 'default_folder.svg';
            } else if (i['logs']) {
                var icon = 'file_type_favicon.svg';
            } else {
                var icon = 'empty.svg';
            }

            $("#maxsim_ide_tree").append('<div class="maxsim_ide_tree_item" data-item="' + i['name'] + '" data-type="' + i['type'] + '"><img src="maxsim/ide/vendor/icons/' + icon + '" width="16" /> ' + i['name'] + '</div>');
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