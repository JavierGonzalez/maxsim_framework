<!doctype html>
<html>
<head>

<title><?=(isset($maxsim['template']['title'])?$maxsim['template']['title']:ucfirst(str_replace('_', ' ', basename($maxsim['app'] ?? '', '.php'))))?></title>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<meta name="description" content="<?=$maxsim['template']['description'] ?? null?>" />

<link rel="icon" href="data:,">

<?php

echo $maxsim['template']['head'] ?? null;

foreach ($maxsim['autoload'] ?? [] AS $file)
	if (substr($file,-4) === '.css')
		echo '<link rel="stylesheet" enctype="text/css" href="/'.$file.'" media="all" />'."\n";

if (isset($maxsim['template']['css']))
	echo '<style type="text/css">'.$maxsim['template']['css'].'</style>';


if (isset($maxsim['template']['js_array'])) {
	echo '<script type="text/javascript">';
	foreach ($maxsim['template']['js_array'] AS $key => $value)
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
        
        <?=$maxsim['template']['top_right'] ?? null?> 
        
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
		echo '<script type="module" src="/'.$file.'"></script>'."\n";
?>

<script type="text/javascript">
<?=$maxsim['template']['js'] ?? null?>
</script>

</body>
</html>