# maxsim 0.5

Maximum Simplicity framework


### Key Features
* Sub-millisecond overhead `20k requests per second`.
* Simple, modular, fast.
* Hello World in one line.
* File-System routing, autoload, tags and events.
* Modular parts are independent isolated product-oriented directories.
* Autoload files with prefix `+` and suffix `.php .js .css .ini`.
* Autoload files inside directories with prefix `+`, recursively.
* Events injection with files anywhere with prefix `!` and suffix `.php`.
* Single-line tests with `.phpt` files anywhere, executed in `/+maxsim/tests`.
* 200 lines single-file framework kernel `+maxsim/maxsim.php`.


### To know
* Experimental, in development.
* Minimum required: `.htaccess` and `+maxsim/maxsim.php`.
* Docker deployment with: `docker-compose -f +maxsim/docker/docker-compose.yml up --build`.
* Framework kernel info in array `$maxsim`.
* Default HTML template output if `!template.php` exists.
* Text-plain output with `exit;`.
* Server-Timing header for performance debug.
* Reserved functions, variables and constants with prefix `maxsim`.
* URL directories beyond the app file are in: `$_GET[1] $_GET[2] ..`.
* Directories can be deleted or moved without modifying code.
* `.ini` files are loaded with a PHP constant named with the file name.
* Autoload `.css` and `.js` files included in template HTML output.
* 404 error when output is null.
* 404 error customized with `!error_404.php`.
* 403 error for files or directories containing `password`, prefix `.` or suffix `.php .phpt .log .ini .env .yml .md`.
* Event files can be triggered with `maxsim_event('name')` for `!name.php`.
* Event injector search limited to 4 directories depth (listed in `$maxsim['events']`).
* Event names available (in execution order): `maxsim_router maxsim_autoload maxsim_autoload_after maxsim_app maxsim_app_after error_404 template maxsim_exit`
* File tags in `$maxsim['tags']` with prefix `#` or `@`.
* Framework app url rewrite with: `$maxsim['redirect'] = '/url';`.
* maxsim version exposed in: `/+maxsim/maxsim`.
* Performance tested with: `ab -n 50000 -c 32 http://localhost:80/hello_world` (1 route, 1 template, Docker, PHP8, 6 cpu).
* Simple, short, light, fast, modular, procedural first, snake_case, product-oriented, business-centric.
* MIT License.


### Future features
* Debugger.
* IDE inside.
* Users system.
* ORM and SQL client.
* Code and app marketplace.


### Tested environment
* PHP >=7.4
* CentOS 7.8 x86_64
* Alpine Linux docker
* Apache (with mod_rewrite)
* MySQL, MariaDB


### Used in
- **[POL](https://github.com/JavierGonzalez/POL)** A democratic social network.
- **[BMP](https://github.com/JavierGonzalez/BMP)** A Bitcoin hashpower voting system.
- Other private projects.


\
Javier González González — <javier.gonzalez@maxsim.tech> \
The maxsim Architect