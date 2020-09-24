<?php

$php = "<?php\n\ninclude('".str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__)."/injection.php');";

file_put_contents('*/ide.php', $php);