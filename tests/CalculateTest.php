<?php
/**
 * The file for the calculate-timeout service tests
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\CalculateTimeout;

use DateInterval;
use Jstewmc\Fx\Constant;
use Jstewmc\Interval\Interval;
use Jstewmc\PiecewiseFx\{PiecewiseFx, SubFx};
use Jstewmc\TestCase\TestCase;

/**
 * Tests for the calculate-timeout service
 */
class CalculateTest extends TestCase
{
    /* __construct() */
    
    /**
     * __construct() should set the service's properties
     */
    public function testConstruct()
    {
        $fx = new PiecewiseFx([]);    
        
        $service = new Calculate($fx);
        
        $this->assertSame($fx, $this->getProperty('fx', $service));
        
        return;
    }
    
    
    /* __invoke() */
    
    /**
     * __invoke() should throw exception if $x is not a number
     */
    public function testInvokeThrowsExceptionIfXIsNotANumber()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Calculate(new PiecewiseFx([])))('foo');
        
        return;
    }
    
    /**
     * __invoke() should return DateInterval if $x is *outside* domain (i.e., 
     *     $y is null)
     */
    public function testInvokeReturnsDateIntervalIfXIsOutsideDomain()
    {
        // define a constant function between -1 and 1
        $fx = new PiecewiseFx([
            new SubFx(
                new Interval('[-1, 1]'),
                new Constant(1)        
            )
        ]);
        
        // calculate the timeout for 999
        $expected = new DateInterval('PT0S');
        $actual   = (new Calculate($fx))(999);
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
    
    /**
     * __invoke() should return DateInterval if $x is *inside* domain (i.e., $y is
     *     not null)
     */
    public function testInvokeReturnsDateIntervalIfXIsInsideDomain()
    {
        $c = 1;
        
        // define a constant function between -1 and 1
        $fx = new PiecewiseFx([
            new SubFx(
                new Interval('[-1, 1]'),
                new Constant($c)        
            )
        ]);
        
        // calculate the timeout for 1
        $expected = new DateInterval("PT{$c}S");
        $actual   = (new Calculate($fx))(0);
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
    
    /**
     * __invoke() should return DateInterval if $y is negative
     */
    public function testInvokeReturnsDateIntervalIfYIsNegative()
    {
        $c = -1;
        
        // define a constant function between -1 and 1
        $fx = new PiecewiseFx([
            new SubFx(
                new Interval('[-1, 1]'),
                new Constant($c)        
            )
        ]);
        
        // calculate the timeout for 1
        $expected = new DateInterval("PT0S");
        $actual   = (new Calculate($fx))(0);
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
    
    /**
     * __invoke() should return DateInterval if $y is float
     */
    public function testInvokeReturnsDateIntervalIfYIsFloat()
    {
        $c = 0.5;
        
        // define a constant function between -1 and 1
        $fx = new PiecewiseFx([
            new SubFx(
                new Interval('[-1, 1]'),
                new Constant($c)        
            )
        ]);
        
        // calculate the timeout for 1
        $expected = new DateInterval("PT1S");
        $actual   = (new Calculate($fx))(0);
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
}
