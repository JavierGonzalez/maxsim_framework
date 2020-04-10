<?php 


/* if (is_array($echo)) {
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($echo, JSON_PRETTY_PRINT);
    exit;
}*/


header('Content-Type:text/html; charset=utf-8');

$output = ob_get_contents();
ob_end_clean();

?>


<html>

<body>

<?=$output?>

</body>

</html>