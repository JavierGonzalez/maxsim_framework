# maxsim iftest

A simple and sophisticated inline code testing framework.

<a href="https://github.com/JavierGonzalez/maxsim_framework/blob/master/iftest/iftest.phpt">Example</a>. \
<a href="https://maxsim.tech/iftest?file=iftest%2Fiftest.phpt">Demo</a>.

### Key Features:
- One test per line!
- On-the-fly execution.
- Displays the test result (useful for debugging).
- Execution time measurement in milliseconds.
- High performance (66K test per second).

### Concept:

Each line of code in a `.phpt` file will be evaluated as a condition within an `if`. 

If the condition is met the test PASS, otherwise the test FAIL.

```php
if (EACH_LINE_CODE_TEST_HERE)
    return true;  // PASS
else 
    return false; // FAIL
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
- Test files anywhere with `.phpt` extension.
- Lines beginning with `//` are ignored.
- Lines beginning with `#` are showed as titles.
- `.phpt` files can start with `<?` to be colored by IDE.
- Test execution recursively with `iftest_phpt('example.phpt')` function.
- Each test can read what has been declared by previous tests in the same file.
- The `#limit_ms=1` tag at the end of line fails if the execution time exceeds `1` millisecond.
- The `#pass_fail` tag at the end of line reverses the test verdict. Normally useless.
- MIT License.


\
The maxsim Architect \
Javier González González &lt;gonzo@virtualpol.com&gt; \
`A544 0B65 C998 9F6F 31A6 5020 DA34 5BEE 8E15 DEC6` \
 \
Simplicity is the Maximum Sophistication