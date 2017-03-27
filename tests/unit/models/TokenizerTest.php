<?php

namespace tests\models;

use app\models\calc\tokens\CosToken;
use app\models\calc\tokens\DivToken;
use app\models\calc\tokens\LParToken;
use app\models\calc\tokens\MinusToken;
use app\models\calc\tokens\MulToken;
use app\models\calc\tokens\NumToken;
use app\models\calc\tokens\OperatorToken;
use app\models\calc\tokens\PiToken;
use app\models\calc\tokens\PlusToken;
use app\models\calc\tokens\PowToken;
use app\models\calc\tokens\RParToken;
use app\models\calc\Tokenizer;
use app\models\calc\tokens\SinToken;
use app\models\calc\tokens\UnaryMinusToken;
use app\models\calc\UnknownLexemeException;
use Codeception\Test\Unit;

class TokenizerTest extends Unit
{
    /**
     * @var Tokenizer
     */
    protected $tokenizer;

    protected function _before()
    {
        $this->tokenizer = new Tokenizer();
    }

    public function testTokenizeEmpty()
    {
        $this->assertEquals([], $this->tokenizer->tokenize(''));
    }

    public function testTokenizeOnlyOneSpace()
    {
        $this->assertEquals([], $this->tokenizer->tokenize(' '));
    }

    public function testTokenizeNumber()
    {
        $this->assertEquals([new NumToken(1, '123')], $this->tokenizer->tokenize('123'));
    }

    public function testTokenizeFloatNumber()
    {
        $this->assertEquals([new NumToken(1, '123.23423')], $this->tokenizer->tokenize('123.23423'));
    }

    public function testTokenizePosition()
    {
        $this->assertEquals([
            new NumToken(1, '123'), new PlusToken(6), new NumToken(9, '22')
        ], $this->tokenizer->tokenize('123  +  22'));
    }

    public function testTokenizeInvalidPosition()
    {
        $this->assertNotEquals([
            new NumToken(1, '123'), new PlusToken(3), new NumToken(9, '22')
        ], $this->tokenizer->tokenize('123  +  22'));
    }

    public function testTokenizeSpaceDivideLexeme()
    {
        $this->assertEquals([
            new NumToken(1, '1'), new NumToken(3, '2'), new NumToken(5, '3')
        ], $this->tokenizer->tokenize('1 2 3'));
    }

    public function testTokenizePlus()
    {
        $this->assertEquals([new PlusToken(1)], $this->tokenizer->tokenize('+'));
    }

    public function testTokenizeUnknownLexeme()
    {
        $this->expectException(UnknownLexemeException::class);
        $this->tokenizer->tokenize('13~+17');
    }

    public function testTokenizeUnknownLexemePosition3()
    {
        $this->expectExceptionMessage('position 3');
        $this->tokenizer->tokenize('13~+17');
    }

    public function testTokenizeUnknownLexemePosition5()
    {
        $this->expectExceptionMessage('position 5');
        $this->tokenizer->tokenize('1343~+17');
    }

    public function testTokenizeUnknownLexemeValue()
    {
        $this->expectExceptionMessage('lexeme "~"');
        $this->tokenizer->tokenize('13~+17');
    }

    public function testTokenizeMinus()
    {
        $this->assertEquals([new NumToken(1, '1'), new MinusToken(2)], $this->tokenizer->tokenize('1-'));
    }

    public function testTokenizeMultiply()
    {
        $this->assertEquals([new MulToken(1)], $this->tokenizer->tokenize('*'));
    }

    public function testTokenizeDivide()
    {
        $this->assertEquals([new DivToken(1)], $this->tokenizer->tokenize('/'));
    }

    public function testTokenizePower()
    {
        $this->assertEquals([new PowToken(1)], $this->tokenizer->tokenize('^'));
    }

    public function testTokenizeLeftParentheses()
    {
        $this->assertEquals([new LParToken(1)], $this->tokenizer->tokenize('('));
    }

    public function testTokenizeRightParentheses()
    {
        $this->assertEquals([new RParToken(1)], $this->tokenizer->tokenize(')'));
    }

    public function testTokenizeIgnoreSpaces()
    {
        $this->assertEquals([
            new NumToken(2, '27'), new MinusToken(6), new NumToken(17, '19')
        ], $this->tokenizer->tokenize(' 27  -          19      '));
    }

    public function testTokenizeSin()
    {
        $this->assertEquals([
            new SinToken(1), new NumToken(5, '1')
        ], $this->tokenizer->tokenize('sin 1'));
        $this->assertEquals([
            new SinToken(1), new LParToken(4), new NumToken(5, '1')
        ], $this->tokenizer->tokenize('sin(1'));

        $this->expectException(UnknownLexemeException::class);
        $this->expectExceptionMessage('lexeme "s"');
        $this->assertEquals([
            new SinToken(1), new NumToken(4, '1')
        ], $this->tokenizer->tokenize('sin1'));
    }

    public function testTokenizeCos()
    {
        $this->assertEquals([
            new CosToken(1), new NumToken(5, '1')
        ], $this->tokenizer->tokenize('cos 1'));
        $this->assertEquals([
            new CosToken(1), new LParToken(4), new NumToken(5, '1')
        ], $this->tokenizer->tokenize('cos(1'));

        $this->expectException(UnknownLexemeException::class);
        $this->expectExceptionMessage('lexeme "c"');
        $this->assertEquals([
            new CosToken(1), new NumToken(4, '1')
        ], $this->tokenizer->tokenize('cos1'));
    }

    public function testTokenizePi()
    {
        $this->assertEquals([
            new SinToken(1), new PiToken(5)
        ], $this->tokenizer->tokenize('sin PI'));
    }

    public function testTokenizeComplex()
    {
        $this->assertEquals([
            new NumToken(1, '12'),
            new MinusToken(3),
            new LParToken(4),
            new LParToken(9),
            new NumToken(10, '2'),
            new PlusToken(13),
            new NumToken(14, '339'),
            new RParToken(17),
            new DivToken(20),
            new NumToken(21, '7'),
            new RParToken(22),
            new MulToken(24),
            new NumToken(31, '47560'),
            new PowToken(36),
            new NumToken(37, '98321')
        ], $this->tokenizer->tokenize('12-(    (2  +339)  /7) *      47560^98321'));
    }

    public function testTokenizeFirstMinusInExpressionIsUnaryOperator()
    {
        $tokens = $this->tokenizer->tokenize('    -12');
        /** @var OperatorToken $minus */
        $minus = $tokens[0];
        $this->assertTrue($minus instanceof UnaryMinusToken);
    }

    public function testTokenizeFirstMinusAfterLeftParenthesesIsUnaryOperator()
    {
        $tokens = $this->tokenizer->tokenize('1+(   -12)');
        /** @var OperatorToken $minus */
        $minus = $tokens[3];
        $this->assertTrue($minus instanceof UnaryMinusToken);
    }
}