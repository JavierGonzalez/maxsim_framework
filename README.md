# maxsim 5.8

Maximum Simplicity Framework


### Features

* 0.6 ms.
* Hello World! is one line.
* URL routing by file system.
* Autoload files with `+` prefix and suffix `php js css ini json`.
* Autoload files inside a directory with `+` prefix, recursively.
* Template with: `$maxsim['output'] = 'template';`
* Framework core in a 123-line single file: `$maxsim.php`


### To know

* In development.
* Minimum required: `.htaccess` and `$maxsim.php`.
* Framework info in `$maxsim` array.
* Reserved functions, variables and constants starting with `maxsim`.
* URL directories beyond the app file are in: `$_GET[1] $_GET[2] ..`
* `ini` files are loaded inside a constant with the file name.
* `json` files are loaded inside a variable with the file name.
* Autoload `css` and `js` files are included in the template.
* Error 404 when app returns null `echo`.
* Optional `404.php` file when 404 error template.
* Framework app redirection with: `$maxsim['redirect'] = '/url';`
* Public access denied for any file containing `password`.
* Text-plain output with: `$maxsim['output'] = 'text';` or `exit;`
* JSON output with: `$maxsim['output'] = 'json';` and array in `$echo`
* Simple, short, lightweight, no PSR, snake_case, procedural first, flexible, expansible, old-style.


### Future features

* Docker.
* Debugger.
* Users system.
* Integrated IDE.
* ORM and SQL client.
* Code and app marketplace.


### Tested environments

* GNU/Linux CentOS 7.8 x86_64
* Apache
* PHP 7.4
* MySQL, MariaDB


Javier González González — gonzo@virtualpol.com