<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>



if (!isset($_GET['file']))
    exit;

if (substr($_GET['file'],0,1) === '/')
    exit;

if (substr($_GET['file'],-5,5) !== '.phpt')
    exit;

if (!file_exists($_GET['file']))
    exit;



ini_set('output_buffering', '0');
ini_set('zlib.output_compression', '0');
ini_set('implicit_flush', 1);
ob_implicit_flush(1);
header('Content-Encoding: none');


echo '<html>
<body>

<style>
.iftest-tr {
    width:100%;
    font-family:monospace;
    font-size:16px;
    background-color:green;
    color:white;
    font-weight:bold;
    padding:2px 10px;
}

.iftest-array {
    background:#EEE;
    text-align:left;
    padding-right:8px;
}
.iftest-result {
    overflow-x:auto;
    max-width:400px;
    min-width:100px;
}
.iftest-code {
    background:#EEE;
    padding:0 0 0 10px;
    word-wrap:break-word;
}
</style>

<div style="display:flex;flex-direction:column-reverse;height:100%;overflow-y:scroll;">

<table>';



iftest_phpt($_GET['file'], true);



if (!isset($unit_test['tests_pass']))
    $unit_test['tests_pass'] = 0;

$unit_test['tests_fail'] = ($unit_test['tests_total'] - $unit_test['tests_pass']);


if ($unit_test['tests_fail'] == 0)
	$test_result_print = '<b style="color:blue;">ALL PASS</b>';
else
	$test_result_print = '<b style="color:red;">FAIL &nbsp; '.num($unit_test['tests_fail']).'</b>';

echo '

<tr>
<td colspan="5">
    <hr />
    <div style="font-size:50px;">
        '.$test_result_print.' 
        <span style="margin-left:64px;font-size:20px;">'.num($unit_test['tests_total']).' tests in '.num($iftest_phpt_crono * 1000, 1) . ' ms</span>
    </div>
</td>
</tr>

</table>

<h1 style="margin-top:1000px;">iftest</h1>

</div>
</body>
</html>';
	
	
exit;