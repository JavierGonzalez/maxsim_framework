<!doctype html>
<html>
<head>

<title><?=$maxsim['template']['title']?></title>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<meta name="description" content="<?=$maxsim['template']['description']?>" />

<?php

foreach ((array)$maxsim['autoload'] AS $file)
	if (substr($file,-4)==='.css')
		echo '<link rel="stylesheet" enctype="text/css" href="/'.$file.'" media="all" />'."\n";

echo '
<style type="text/css">
'.$maxsim['template']['css'].'
</style>';

?>


<script type="text/javascript">
<?php
foreach ((array)$maxsim['template']['js_array'] AS $key => $value)
    echo $key.' = "'.str_replace('"', '\"', $value).'";'."\n";
?>
</script>

</head>


<body>


<div id="content_left">
	
	
</div>



<div id="content_right">


    <div id="top_right">
        
        <?=$maxsim['template']['top_right']?> 
        
        <span id="print_login"></span>

    </div>


	<div id="content">
    
	    <?=$echo?>

	</div>


	<div id="footer" style="color:#777;">


	</div>

</div>



<?php
foreach ((array)$maxsim['autoload'] AS $file)
	if (substr($file,-3)==='.js')
		echo '<script type="module" src="/'.$file.'"></script>'."\n";
?>

<script type="text/javascript">
<?=$maxsim['template']['js']?>
</script>

</body>
</html>