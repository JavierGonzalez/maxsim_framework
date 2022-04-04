# maxsim 0.5

Simplicity is the ultimate sophistication.


### Features
* 48 µs  `20k request per second`.
* Simple, fast, modular.
* Hello World in one line.
* FileSystem-based routing.
* Autoload files with prefix `+` and suffix `.php .js .css .ini .json`.
* Autoload files inside a directory with prefix `+`, recursively.
* Template selector with: `$maxsim['output'] = 'template';`
* 123-lines single-file framework kernel.


### To know
* Experimental, undocumented, in alpha development!
* Minimum required: `.htaccess` and `$maxsim.php`.
* Docker deployment with: `docker-compose build` and `docker-compose up`.
* Routing info in array `$maxsim`.
* Server-Timing header for performance debug.
* Reserved functions, variables and constants starting with `maxsim`.
* URL directories beyond the app file are in: `$_GET[1] $_GET[2] ..`.
* Autoload `css` and `js` files are included in the template.
* `ini` files are loaded with a constant named with the file name.
* `json` files are loaded with a variable named with the file name.
* 404 error when app print output is null.
* Access denied for any file name containing `password`.
* Framework app url redirection with: `$maxsim['redirect'] = '/url';`.
* Text-plain output with: `exit;` or `$maxsim['output'] = 'text';`.
* JSON output when `$echo` is array and `$maxsim['output'] = 'json';`.
* Performance tested with: `ab -n 50000 -c 32 http://localhost:80/hello_world` (minimal branch, 1 route, 1 template, Alpine docker, PHP 8.1, 8MB RAM).
* Simple, short, light, fast, modular, procedural first, snake_case.


### Future features
* Debugger.
* IDE inside.
* Users system.
* ORM and SQL client.
* Code and app marketplace.


### Tested environment
* PHP >7.4
* Alpine Linux docker
* CentOS 7.8 x86_64
* Apache (with mod_rewrite)
* MySQL, MariaDB

### Used in
- **[POL](https://github.com/JavierGonzalez/POL)** A democratic voting community.
- **[BMP](https://github.com/JavierGonzalez/BMP)** A Bitcoin hashpower voting system.

Javier González González <gonzo@virtualpol.com>
