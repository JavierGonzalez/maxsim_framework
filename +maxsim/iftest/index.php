<?php


$test_fail_total_num = 0;

$test_files_raw = glob('{*,*/*,*/*/*,*/*/*/*,*/*/*/*/*}.iftest', GLOB_BRACE);

foreach ($test_files_raw AS $test_file) {
    $name = explode('.', basename($test_file))[0];
    $test_files[$name] = $test_file;
}

ksort($test_files);

foreach ($test_files AS $name => $test_file) {
	
    $test_fail_num = 0;

	$nav_tabs_li[$name] = '
        <div class="iftest-box-num" style="background-color:'.($test_fail_num===0?'blue':'red').';">'.($test_fail_num===0?'':$test_fail_num).'</div>
        <a href="'.$maxsim['app_url'].'?file='.urlencode($test_file).'" 
        style="text-decoration:none;padding-top:3px;padding-bottom:3px;'.($test_file==($_GET['file'] ?? null)?'font-weight:bold;':'').'" title="'.$test_file.'">'.$name.'</a>
        <br />';
	
	$test_fail_total_num += $test_fail_num;
}

echo '

<style>
.iftest-box-num { 
    display: inline-block;
    margin: 0;
    width: 24px;
    height: 24px;
    color: white;
    text-align: center;
    vertical-align: middle;
    font-weight: bold; 
}
</style>



<table width="100%" border=0><tr><td valign=top align=left style="min-width:200px; padding:20px 10px 0 0;">


<div>

<div class="iftest-box-num" style="font-size:20px;width:100%;height:75px;background-color:'.($test_fail_total_num===0?'blue':'red').';">
<div style="position: relative; top: 50%; -webkit-transform: translateY(-50%); -ms-transform: translateY(-50%); transform: translateY(-50%);">
'.($test_fail_total_num===0?'':$test_fail_total_num).'
</div>
</div><br />
    
'.implode("\n", $nav_tabs_li).'
		
<br />
</div>

</td><td valign=top width="100%" style="padding:20px 10px 0 0;">';
		
if ($_GET['file'] ?? null) {
    echo '<div>
        <iframe id="iftest-iframe" src="'.$maxsim['app_url'].'/exec?file='.urlencode($_GET['file']).'" frameborder="0" style="border:none; height:90vh; width:100%;">
            <script type="text/javascript">
                document.getElementById("iftest-iframe").onload = function() {
                    alert("ok");
                }
            </script>
        </iframe>
        </div>';
}

echo '</td></tr></table>';