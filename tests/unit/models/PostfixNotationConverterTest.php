<?php

namespace tests\models;

use app\models\calc\PostfixNotationConverter;
use app\models\calc\Tokenizer;
use app\models\calc\tokens\DivToken;
use app\models\calc\tokens\MinusToken;
use app\models\calc\tokens\MulToken;
use app\models\calc\tokens\NumToken;
use app\models\calc\tokens\PlusToken;
use app\models\calc\tokens\PowToken;
use Codeception\Test\Unit;

class PostfixNotationConverterTest extends Unit
{
    /**
     * @var PostfixNotationConverter
     */
    protected $notation;

    protected function _before()
    {
        $this->notation = new PostfixNotationConverter(new Tokenizer());
    }

    public function testConvertEmpty()
    {
        $this->assertEquals([], $this->notation->convert(''));
    }

    public function testConvertOnlyOneSpace()
    {
        $this->assertEquals([], $this->notation->convert(' '));
    }

    public function testConvertAdditionOfTwoNumber()
    {
        $this->assertEquals([
            new NumToken(1, '2'), new NumToken(3, '3'), new PlusToken(2)
        ], $this->notation->convert('2+3'));
    }

    public function testConvertIgnoreSpaces()
    {
        $this->assertEquals([
            new NumToken(1, '2'), new NumToken(6, '3'), new PlusToken(4)
        ], $this->notation->convert('2  + 3'));
    }

    public function testConvertAdditionOfTwoNumberWithParenthesis()
    {
        $this->assertEquals([
            new NumToken(2, '2'), new NumToken(4, '3'), new PlusToken(3)
        ], $this->notation->convert('(2+3)'));
    }

    public function testConvertTwoOperator()
    {
        $this->assertEquals([
            new NumToken(1, '2'), new NumToken(3, '3'), new PlusToken(2), new NumToken(5, '1'), new MinusToken(4)
        ], $this->notation->convert('2+3-1'));
    }

    public function testConvertTwoOperatorWithParenthesis()
    {
        $this->assertEquals([
            new NumToken(2, '2'), new NumToken(4, '3'), new PlusToken(3), new NumToken(6, '1'), new MinusToken(5)
        ], $this->notation->convert('(2+3-1)'));
    }

    public function testConvertMissingRightParentheses()
    {
        $this->expectException(\Exception::class);
        $this->notation->convert('2+3)*1');
    }

    public function testConvertMissingLeftParentheses()
    {
        $this->expectException(\Exception::class);
        $this->notation->convert('2+(3*1');
    }

    public function testConvertTwoOperatorWithDifferentPrecedence()
    {
        $this->assertEquals([
            new NumToken(1, '2'), new NumToken(3, '3'), new NumToken(5, '1'), new MulToken(4), new PlusToken(2)
        ], $this->notation->convert('2+3*1'));
    }

    public function testConvertTwoOperatorWithDifferentAssociativity()
    {
        $this->assertEquals([
            new NumToken(1, '2'), new NumToken(3, '3'), new NumToken(5, '1'), new PowToken(4), new PowToken(2)
        ], $this->notation->convert('2^3^1'));
    }

    public function testConvertComplex()
    {
        $this->assertEquals([
            new NumToken(1, '3'),
            new NumToken(3, '4'),
            new NumToken(5, '2'),
            new MulToken(4),
            new NumToken(8, '1'),
            new NumToken(10, '5'),
            new MinusToken(9),
            new NumToken(13, '2'),
            new NumToken(15, '3'),
            new PowToken(14),
            new PowToken(12),
            new DivToken(6),
            new PlusToken(2),
        ], $this->notation->convert('3+4*2/(1-5)^2^3'));
    }
}