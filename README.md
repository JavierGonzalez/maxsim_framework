# maxsim 0.5

Maximum Simplicity framework


### Features
* Sub-millisecond overhead `20k requests per second`.
* Simple, modular, fast.
* Hello World in one line.
* File-System routing, autoload and events.
* Autoload files with prefix `+` and suffix `.php .js .css .ini`.
* Autoload files inside a directory with prefix `+`, recursively.
* Events injection with files everywhere with prefix `!` and suffix `.php`.
* Single-line tests with `.phpt` files everywhere, execution in `/maxsim/tests`.
* 180-line single-file framework kernel.


### To know
* Experimental, alpha.
* Minimum required: `.htaccess` and `$maxsim.php`.
* Docker deployment with: `docker-compose build` and `docker-compose up`.
* Framework kernel info in `$maxsim` array.
* Server-Timing header for performance debug.
* Reserved functions, variables and constants with `maxsim` prefix.
* URL directories beyond the app file are in: `$_GET[1] $_GET[2] ..`.
* Autoload `css` and `js` files are included in template.
* `ini` files are loaded with a constant named with the file name.
* 404 error when app print output is null.
* Access denied for any file name containing `password`.
* Framework app url rewrite with: `$maxsim['redirect'] = '/url';`.
* Text-plain output with: `exit;`.
* Performance test with: `ab -n 50000 -c 32 http://localhost:80/hello_world` (1 route, 1 template, Alpine docker, PHP 8.1, 8 threads).
* Simple, short, light, fast, modular, procedural first, snake_case, product-oriented.


### Future features
* Debugger.
* IDE inside.
* Users system.
* ORM and SQL client.
* Code and app marketplace.


### Tested environment
* PHP >=7.4
* Alpine Linux docker
* CentOS 7.8 x86_64
* Apache (with mod_rewrite)
* MySQL, MariaDB

### Used in
- **[POL](https://github.com/JavierGonzalez/POL)** A democratic voting community.
- **[BMP](https://github.com/JavierGonzalez/BMP)** A Bitcoin hashpower voting system.
- Other private projects.


Javier González González <gonzo@virtualpol.com> 
maxsim Architect