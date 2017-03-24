<?php

namespace tests\models;

use app\models\tokens\DivToken;
use app\models\tokens\LParToken;
use app\models\tokens\MinusToken;
use app\models\tokens\MulToken;
use app\models\tokens\NumToken;
use app\models\tokens\PlusToken;
use app\models\tokens\PowToken;
use app\models\tokens\RParToken;
use app\models\Tokenizer;
use app\models\UnknownLexemeException;
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
        $this->assertEquals($this->tokenizer->tokenize(''), []);
    }

    public function testTokenizeOnlyOneSpace()
    {
        $this->assertEquals($this->tokenizer->tokenize(' '), []);
    }

    public function testTokenizeNumber()
    {
        $this->assertEquals($this->tokenizer->tokenize('123'), [new NumToken(1, '123')]);
    }

    public function testTokenizeFloatNumber()
    {
        $this->assertEquals($this->tokenizer->tokenize('123.23423'), [new NumToken(1, '123.23423')]);
    }

    public function testTokenizePosition()
    {
        $this->assertEquals($this->tokenizer->tokenize('123  +  22'), [
            new NumToken(1, '123'), new PlusToken(6), new NumToken(9, '22')
        ]);
    }

    public function testTokenizeInvalidPosition()
    {
        $this->assertNotEquals($this->tokenizer->tokenize('123  +  22'), [
            new NumToken(1, '123'), new PlusToken(3), new NumToken(9, '22')
        ]);
    }

    public function testTokenizeSpaceDivideLexeme()
    {
        $this->assertEquals($this->tokenizer->tokenize('1 2 3'), [
            new NumToken(1, '1'), new NumToken(3, '2'), new NumToken(5, '3')
        ]);
    }

    public function testTokenizePlus()
    {
        $this->assertEquals($this->tokenizer->tokenize('+'), [new PlusToken(1)]);
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
        $this->assertEquals($this->tokenizer->tokenize('-'), [new MinusToken(1)]);
    }

    public function testTokenizeMultiply()
    {
        $this->assertEquals($this->tokenizer->tokenize('*'), [new MulToken(1)]);
    }

    public function testTokenizeDivide()
    {
        $this->assertEquals($this->tokenizer->tokenize('/'), [new DivToken(1)]);
    }

    public function testTokenizePower()
    {
        $this->assertEquals($this->tokenizer->tokenize('^'), [new PowToken(1)]);
    }

    public function testTokenizeLeftParentheses()
    {
        $this->assertEquals($this->tokenizer->tokenize('('), [new LParToken(1)]);
    }

    public function testTokenizeRightParentheses()
    {
        $this->assertEquals($this->tokenizer->tokenize(')'), [new RParToken(1)]);
    }

    public function testTokenizeIgnoreSpaces()
    {
        $this->assertEquals($this->tokenizer->tokenize(' 27  -          19      '), [
            new NumToken(2, '27'), new MinusToken(6), new NumToken(17, '19')
        ]);
    }

    public function testTokenizeComplex()
    {
        $this->assertEquals($this->tokenizer->tokenize('12-(    (2  +339)  /7) *      47560^98321'),  [
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
        ]);
    }

}