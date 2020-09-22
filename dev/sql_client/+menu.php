<?php


echo '<div style="display:inline-block;left:10px;width:100px;">';

foreach (sql_get_tables() AS $table) {
    echo html_a('/sql_client/table/'.$table, $table).html_br();
}

echo '</div>';