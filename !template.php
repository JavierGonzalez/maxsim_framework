<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <javier.gonzalez@maxsim.tech>

global $template;

$echo = ob_get_contents();
ob_end_clean();


?><!doctype html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<title><?=($template['title'] ?? ucwords(trim(str_replace(['_', '/'], ' ', $maxsim['app_url'] ?? ''))))?></title>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<meta name="description" content="<?=$template['description'] ?? ''?>" />

<link rel="icon" href="data:,">

<?php

echo $template['head'] ?? '';

foreach ($maxsim['autoload'] ?? [] AS $file)
	if (substr($file,-4) === '.css')
		echo '<link rel="stylesheet" enctype="text/css" href="/'.$file.'" media="all" />'."\n";

if (isset($template['css']))
	echo '<style type="text/css">'.$template['css'].'</style>';


if (isset($template['js_array'])) {
	echo '<script type="text/javascript">';
	foreach ($template['js_array'] AS $key => $value)
		echo $key.' = "'.str_replace('"', '\"', $value).'";'."\n";
	echo '</script>';
}

?>

</head>


<body>


<div id="content_left">
	
	
</div>



<div id="content_right">


    <div id="top_right">
        
        <?=$template['top_right'] ?? ''?> 
        
        <span id="print_login"></span>

    </div>


	<div id="content">
    
        <?=$echo?>

	</div>


	<div id="footer" style="color:#777;">


	</div>

</div>



<?php
foreach ($maxsim['autoload'] ?? [] AS $file)
	if (substr($file,-3)==='.js')
		echo '<script src="/'.$file.'"></script>'."\n";
?>

<?php
if (isset($template['js']))
    echo '<script type="text/javascript">'.implode("\n", (array) $template['js']).'</script>';
?>

</body>
</html>