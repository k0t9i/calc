<?php

namespace tests\models;

use app\models\calc\CalculateSyntaxException;
use app\models\calc\PostfixNotationConverter;
use app\models\calc\PostfixNotationCalculator;
use app\models\calc\Tokenizer;
use Codeception\Test\Unit;

class PostfixNotationCalculatorTest extends Unit
{
    /**
     * @var PostfixNotationCalculator
     */
    protected $calculator;

    protected function _before()
    {
        $this->calculator = new PostfixNotationCalculator(new PostfixNotationConverter(new Tokenizer()));
    }

    public function testCalculateEmpty()
    {
        $this->assertEquals(0, $this->calculator->calculate(''));
    }

    public function testCalculateOnlyOneSpace()
    {
        $this->assertEquals(0, $this->calculator->calculate(' '));
    }

    public function testCalculateAdditionOfTwoNumber()
    {
        $this->assertEquals(5, $this->calculator->calculate('2+3'));
    }

    public function testCalculateSubtractionOfTwoNumber()
    {
        $this->assertEquals(1, $this->calculator->calculate('3-2'));
    }

    public function testCalculateMultiplyOfTwoNumber()
    {
        $this->assertEquals(21, $this->calculator->calculate('7*3'));
    }

    public function testCalculateDivisionOfTwoNumber()
    {
        $this->assertEquals(4, $this->calculator->calculate('8/2'));
    }

    public function testCalculateDivisionOfTwoNumberWithFloatResult()
    {
        $this->assertEquals(2.5, $this->calculator->calculate('10/4'));
    }

    public function testCalculatePowerOfTwoNumber()
    {
        $this->assertEquals(16, $this->calculator->calculate('2^4'));
    }

    public function testCalculateTwoSubtractions()
    {
        $this->assertEquals(4, $this->calculator->calculate('11-2-5'));
    }

    public function testCalculateWithParenthesis()
    {
        $this->assertEquals(9, $this->calculator->calculate('(1+2)*3'));
    }

    public function testCalculateNotEnoughArgs()
    {
        $this->expectException(CalculateSyntaxException::class);
        $this->calculator->calculate('3++2');
    }

    public function testCalculateExcessArgs()
    {
        $this->expectException(CalculateSyntaxException::class);
        $this->calculator->calculate('3+2 3+2');
    }

    public function testCalculateUnaryMinusAtBeginOfExpression()
    {
        $this->assertEquals(-6, $this->calculator->calculate('-2*3'));
    }

    public function testCalculateUnaryMinusAfterLeftParentheses()
    {
        $this->assertEquals(-10, $this->calculator->calculate('5*(-2)'));
    }

    public function testCalculateComplex()
    {
        $this->assertEquals(-3.0001220703125, $this->calculator->calculate('-3+4*(-2)/(1-5)^2^3'));
    }
}