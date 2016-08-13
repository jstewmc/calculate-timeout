# calculate-timeout
Calculate a timeout. 

To defend against brute force attacks, some actions should be throttled. 

Given a timeout [piecewise function](https://github.com/jstewmc/piecewise-fx) and a value, this library will calculate the number of seconds a client should wait before trying an action again. 

For example, consider a login action whose timeout depends on the number of consecutive failed login attempts:

```php
use Jstewmc\Fx\Constant;
use Jstewmc\Interval\Interval;
use Jstewmc\PiecewiseFx\{PiecewiseFx, SubFx};

// define our timeout piecewise function...
// for values between 10 (exclusive) and positive infinity (INF), we want the user
//     to wait for 30 seconds
//
$fx = new PiecewiseFx([
    new SubFx(
        new Interval('(10, INF)'),
        new Constant(30)
    )   
]);

// create our calculate-timeout service
$service = new Calculate($fx);

// check the user's timeout...
// keep in mind, the actual return value is a DateInterval
//
$service(1);   // returns 0 seconds
$service(2);   // returns 0 seconds
$service(3);   // returns 0 seconds
$service(4);   // returns 0 seconds
$service(5);   // returns 0 seconds
$service(6);   // returns 0 seconds
$service(7);   // returns 0 seconds
$service(8);   // returns 0 seconds
$service(9);   // returns 0 seconds
$service(10);  // returns 0 seconds
$service(11);  // returns 30 seconds
$service(12);  // returns 30 seconds
$service(13);  // returns 30 seconds
```

The timeout will always be a `DateInterval` object with zero or a positive number of seconds.

That's it!

## License

[MIT](https://github.com/jstewmc/calculate-timeout/blob/master/LICENSE)

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## Version

### 0.1.0, August 16, 2016

* Initial release
