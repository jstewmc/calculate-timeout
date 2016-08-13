<?php
/**
 * The file for the calculate-timeout service
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\CalculateTimeout;

use DateInterval;
use InvalidArgumentException;
use Jstewmc\PiecewiseFx\PiecewiseFx;

/**
 * The calculate-timeout service
 *
 * @since  0.1.0
 */
class Calculate
{
    /* !Private properties */
    
    /**
     * @var    PiecewiseFx  the timeout function
     * @since  0.1.0
     */
    private $fx;
    
    
    /* !Magic methods */
    
    /**
     * Called when the class is constructed
     *
     * @param  PiecewiseFx  $fx  the timeout function
     * @since  0.1.0
     */
    public function __construct(PiecewiseFx $fx)
    {
        $this->fx = $fx;
    }
       
    /**
     * Called when the class is treated like a function
     *
     * @param   int|float  $x  the x-variable
     * @return  DateInterval
     * @throws  InvalidArgumentException  if $x is not a number
     * @since   0.1.0
     */
    public function __invoke($x): DateInterval
    {
        // if $x is not a number, short-circuit
        if ( ! is_numeric($x)) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, x, to be a number"
            );
        }
        
        // otherwise, calculate the timeout's *raw* value
        $y = ($this->fx)($x);
        
        // convert the *raw* value to a *valid* timeout...
        // keep in mind, a valid timeout is zero or a positive integer
        //
        $timeout = $y !== null ? ceil(max($y, 0)) : 0;
        
        // create a date-time interval
        $interval = new DateInterval("PT{$timeout}S");
        
        return $interval;
    }
}
