# maxsim iftest

A simple and sophisticated inline code testing framework.

<a href="https://github.com/JavierGonzalez/maxsim_framework/blob/master/%2Bmaxsim/iftest/iftest.php.iftest">Example</a>. \
<a href="https://maxsim.tech/+maxsim/iftest?file=%2Bmaxsim%2Fiftest%2Fiftest.php.iftest">Demo</a>.

### Key Features:
- One test per line!
- On-the-fly execution.
- Displays the test result (useful for debugging).
- Execution time measurement in milliseconds.
- High performance (66K test per second).

### Concept:

Each line of code in a `.iftest` file will be evaluated as a condition within an `if`. 

If the condition is met the test PASS, otherwise the test FAIL.

```php
if (EACH_LINE_CODE_TEST_HERE)
    return true;  // TEST PASS
else 
    return false; // TEST FAIL
```

This concept is applicable to almost any language. This is a PHP implementation.


### Examples:

PASS tests:
```php
true
1
'abc' === 'abc'
3 === 1 + 2
is_array(glob('*'))
```

FAIL tests:
```php
false
0
'' === null
true === 1
!is_readable('.')
sleep(1) #limit_ms=50
```


### To know:
- In development.
- Tested with `PHP 8.4` and `GNU/Linux Ubuntu 24.04`.
- Test files anywhere with `.iftest` extension.
- Lines beginning with `//` are ignored.
- Lines beginning with `#` are showed as titles.
- `.iftest` files can start with `<?` to be colored by IDE.
- Test execution recursively with `iftest_phpt('example.php.iftest')` function.
- Each test can read what has been declared by previous tests in the same file.
- The `#limit_ms=1` tag at the end of line fails if the execution time exceeds `1` millisecond.
- The `#pass_fail` tag at the end of line reverses the test verdict. Normally useless.
- MIT License.


\
The maxsim Architect \
Javier González &lt;gonzo@virtualpol.com&gt; \
`A3AD 4AC5 F252 8190 65A5 75A0 B9C3 5FBF 43B3 19C2` \
 \
Simplicity is the Maximum Sophistication