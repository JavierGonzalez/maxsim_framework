<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>

$php = "<?php\n\ninclude('".str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__)."/injection.php');";

file_put_contents('+/ide.php', $php);