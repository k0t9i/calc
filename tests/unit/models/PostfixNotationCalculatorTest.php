<?php

namespace tests\models;

use app\models\calc\CalculateSyntaxException;
use app\models\calc\PostfixNotation;
use app\models\calc\PostfixNotationCalculator;
use app\models\calc\Tokenizer;
use Codeception\Test\Unit;

class PostfixNotationCalculatorTest extends Unit
{
    /**
     * @var Tokenizer
     */
    protected $tokenizer;

    /**
     * @var PostfixNotation
     */
    protected $notation;

    /**
     * @var PostfixNotationCalculator
     */
    protected $calculator;

    protected function _before()
    {
        $this->tokenizer = new Tokenizer();
        $this->notation = new PostfixNotation();
        $this->calculator = new PostfixNotationCalculator();
    }

    public function testCalculateEmpty()
    {
        $infix = $this->tokenizer->tokenize('');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(0, $this->calculator->calculate($postfix));
    }

    public function testCalculateOnlyOneSpace()
    {
        $infix = $this->tokenizer->tokenize(' ');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(0, $this->calculator->calculate($postfix));
    }

    public function testCalculateAdditionOfTwoNumber()
    {
        $infix = $this->tokenizer->tokenize('2+3');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(5, $this->calculator->calculate($postfix));
    }

    public function testCalculateSubtractionOfTwoNumber()
    {
        $infix = $this->tokenizer->tokenize('3-2');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(1, $this->calculator->calculate($postfix));
    }

    public function testCalculateMultiplyOfTwoNumber()
    {
        $infix = $this->tokenizer->tokenize('7*3');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(21, $this->calculator->calculate($postfix));
    }

    public function testCalculateDivisionOfTwoNumber()
    {
        $infix = $this->tokenizer->tokenize('8/2');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(4, $this->calculator->calculate($postfix));
    }

    public function testCalculateDivisionOfTwoNumberWithFloatResult()
    {
        $infix = $this->tokenizer->tokenize('10/4');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(2.5, $this->calculator->calculate($postfix));
    }

    public function testCalculatePowerOfTwoNumber()
    {
        $infix = $this->tokenizer->tokenize('2^4');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(16, $this->calculator->calculate($postfix));
    }

    public function testCalculateTwoSubtractions()
    {
        $infix = $this->tokenizer->tokenize('11-2-5');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(4, $this->calculator->calculate($postfix));
    }

    public function testCalculateWithParenthesis()
    {
        $infix = $this->tokenizer->tokenize('(1+2)*3');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(9, $this->calculator->calculate($postfix));
    }

    public function testCalculateNotEnoughArgs()
    {
        $this->expectException(CalculateSyntaxException::class);
        $infix = $this->tokenizer->tokenize('3++2');
        $postfix = $this->notation->convert($infix);
        $this->calculator->calculate($postfix);
    }

    public function testCalculateExcessArgs()
    {
        $this->expectException(CalculateSyntaxException::class);
        $infix = $this->tokenizer->tokenize('3+2 3+2');
        $postfix = $this->notation->convert($infix);
        $this->calculator->calculate($postfix);
    }

    public function testCalculateUnaryMinusAtBeginOfExpression()
    {
        $infix = $this->tokenizer->tokenize('-2*3');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(-6, $this->calculator->calculate($postfix));
    }

    public function testCalculateUnaryMinusAfterLeftParentheses()
    {
        $infix = $this->tokenizer->tokenize('5*(-2)');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(-10, $this->calculator->calculate($postfix));
    }

    public function testCalculateComplex()
    {
        $infix = $this->tokenizer->tokenize('-3+4*(-2)/(1-5)^2^3');
        $postfix = $this->notation->convert($infix);

        $this->assertEquals(-3.0001220703125, $this->calculator->calculate($postfix));
    }
}