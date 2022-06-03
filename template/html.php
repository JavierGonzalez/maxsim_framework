<!doctype html>
<html>
<head>

<title><?=($template['title'] ?? ucwords(trim(str_replace(['_', '/'], ' ', $maxsim['app_url'] ?? ''))))?></title>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<meta name="description" content="<?=$template['description'] ?? null?>" />

<link rel="icon" href="data:,">

<?php

echo $template['head'] ?? null;

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
        
        <?=$template['top_right'] ?? null?> 
        
        <span id="print_login"></span>

    </div>


	<div id="content">
    
<?php

if ($echo === '') {
	http_response_code(404);
	echo 'Error 404: NOT FOUND';
} else {
	echo $echo;
}

?>

	</div>


	<div id="footer" style="color:#777;">


	</div>

</div>



<?php
foreach ($maxsim['autoload'] ?? [] AS $file)
	if (substr($file,-3)==='.js')
		echo '<script src="/'.$file.'"></script>'."\n";
?>

<script type="text/javascript">
<?=$template['js'] ?? null?>
</script>

</body>
</html>