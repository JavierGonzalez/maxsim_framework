<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


$sql = 'SELECT * FROM '.e($_GET[1]).' LIMIT 25';

$result = sql($sql);

echo $sql.html_br();
echo '<div>';
echo html_table($result);
echo '</div>';
