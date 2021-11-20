# maxsim 0.5

Maximum Simplicity framework

### Features
* 55 µs  `18k request per second`.
* Simple.
* Modular.
* Filesystem-based routing.
* Hello World! in one line.
* Autoload files with prefix `+` and suffix `.php .js .css .ini .json`.
* Autoload files inside a directory with prefix `+`, recursively.
* Template selection with: `$maxsim['output'] = 'template';`
* Framework core in a 123-line single-file called: `$maxsim.php`


### To know
* Experimental, undocumented, in alpha development!
* Minimum required: `.htaccess` and `$maxsim.php`.
* Docker deployment with: `docker-compose build && docker-compose up` (alpinelinux, 19 MB RAM).
* Routing info in array `$maxsim`.
* Server-Timing header for performance debug.
* Reserved functions, variables and constants starting with `maxsim`.
* URL directories beyond the app file are in: `$_GET[1] $_GET[2] ..`
* Autoload `css` and `js` files are included in the template.
* `ini` files are loaded with a constant named with the file name.
* `json` files are loaded with a variable named with the file name.
* 404 error when app print is null.
* Access denied for any file name containing `password`.
* Framework app url redirection with: `$maxsim['redirect'] = '/url';`
* Text-plain output with: `exit;` or `$maxsim['output'] = 'text';`
* JSON API output when `$echo` is array and `$maxsim['output'] = 'json';`.
* Performance tested with: `ab -n 50000 -c 32 http://localhost:80/hello_world` (1 route, minimum, Alpine Linux docker, php 8.0, 9 MB RAM, 12 cores, HDD)
* Simple, short, lightweight, snake_case, procedural first, flexible, expansible, old school.


### Future features
* Debugger.
* IDE inside.
* Users system.
* ORM and SQL client.
* Code and app marketplace.


### Tested environment
* PHP 8.0
* Alpine Linux docker
* CentOS 7.8 x86_64
* Apache
* MySQL, MariaDB

### Used in
- **[POL](https://github.com/JavierGonzalez/POL)** A democratic voting community.
- **[BMP](https://github.com/JavierGonzalez/BMP)** A Bitcoin hashpower voting system.

Javier González González <gonzo@virtualpol.com>