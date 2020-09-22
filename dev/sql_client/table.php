<?php


$sql = 'SELECT * FROM '.$_GET[1].' LIMIT 25';

$result = sql($sql);

echo $sql.html_br();
echo '<div>';
echo html_table($result);
echo '</div>';
