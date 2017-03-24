<?php

namespace tests\models;

use app\models\Lexeme;
use app\models\tokens\DivToken;
use app\models\tokens\LParToken;
use app\models\tokens\MinusToken;
use app\models\tokens\MulToken;
use app\models\tokens\NumToken;
use app\models\tokens\PlusToken;
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

    protected function assertEqualArrayOfObject($first, $second, $strict = false, $message = '')
    {
        $this->assertArraySubset($first, $second, $strict, $message);
        $this->assertArraySubset($second, $first, $strict, $message);
    }

    protected function _before()
    {
        $this->tokenizer = new Tokenizer();
    }

    public function testParseLexemesEmpty()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes(''), []);
    }

    public function testParseLexemesOnlyOneSpace()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes(' '), []);
    }
    
    public function testParseLexemesNumber()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('1'), [new Lexeme('1', 1)]);
    }

    public function testParseLexemesLongNumber()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('123'), [new Lexeme('123', 1)]);
    }

    public function testParseLexemesFloatNumber()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('13333.24444'), [new Lexeme('13333.24444', 1)]);
    }

    public function testParseLexemesLexemePosition()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('123-2'), [
            new Lexeme('123', 1), new Lexeme('-', 4), new Lexeme('2', 5)
        ]);
    }

    public function testParseLexemesSpaceDivideLexeme()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('1 2'), [new Lexeme('1', 1), new Lexeme('2', 3)]);
    }

    public function testParseLexemesSimplePlus()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('1+2'), [
            new Lexeme('1', 1), new Lexeme('+', 2), new Lexeme('2', 3)
        ]);
    }

    public function testParseLexemesPlus()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('132+17'), [
            new Lexeme('132', 1), new Lexeme('+', 4), new Lexeme('17', 5)
        ]);
    }

    public function testParseLexemesUnknownLexeme()
    {
        $this->expectException(UnknownLexemeException::class);
        $this->tokenizer->parseLexemes('13~+17');
    }

    public function testParseLexemesMinus()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('554-1024'), [
            new Lexeme('554', 1), new Lexeme('-', 4), new Lexeme('1024', 5)
        ]);
    }

    public function testParseLexemesMultiply()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('337*123'), [
            new Lexeme('337', 1), new Lexeme('*', 4), new Lexeme('123', 5)
        ]);
    }

    public function testParseLexemesDivide()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('654/19'), [
            new Lexeme('654', 1), new Lexeme('/', 4), new Lexeme('19', 5)
        ]);
    }

    public function testParseLexemesParenthesis()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('(23-(33))'), [
            new Lexeme('(', 1),
            new Lexeme('23', 2),
            new Lexeme('-', 4),
            new Lexeme('(', 5),
            new Lexeme('33', 6),
            new Lexeme(')', 8),
            new Lexeme(')', 9)
        ]);
    }

    public function testParseLexemesIgnoreSpaces()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes(' 27  -          19      '), [
            new Lexeme('27', 2), new Lexeme('-', 6), new Lexeme('19', 17)
        ]);
    }

    public function testParseLexemesUnknownLexemePosition3()
    {
        $this->expectExceptionMessage('position 3');
        $this->tokenizer->parseLexemes('13~+17');
    }

    public function testParseLexemesUnknownLexemePosition5()
    {
        $this->expectExceptionMessage('position 5');
        $this->tokenizer->parseLexemes('1343~+17');
    }

    public function testParseLexemesUnknownLexemeValue()
    {
        $this->expectExceptionMessage('lexeme "~"');
        $this->tokenizer->parseLexemes('13~+17');
    }

    public function testParseLexemesComplex()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->parseLexemes('12-(    (2  +339)  /7) *      4756098321'), [
            new Lexeme('12', 1),
            new Lexeme('-', 3),
            new Lexeme('(', 4),
            new Lexeme('(', 9),
            new Lexeme('2', 10),
            new Lexeme('+', 13),
            new Lexeme('339', 14),
            new Lexeme(')', 17),
            new Lexeme('/', 20),
            new Lexeme('7', 21),
            new Lexeme(')', 22),
            new Lexeme('*', 24),
            new Lexeme('4756098321', 31)
        ]);
    }

    public function testTokenizeEmpty()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize(''), []);
    }

    public function testTokenizeOnlyOneSpace()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize(' '), []);
    }

    public function testTokenizeNumber()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('123'), [new NumToken(1, '123')]);
    }

    public function testTokenizeFloatNumber()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('123.23423'), [new NumToken(1, '123.23423')]);
    }

    public function testTokenizeSpaceDivideLexeme()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('1 2 3'), [
            new NumToken(1, '1'), new NumToken(3, '2'), new NumToken(5, '3')
        ]);
    }

    public function testTokenizePlus()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('+'), [new PlusToken(1)]);
    }

    public function testTokenizeMinus()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('-'), [new MinusToken(1)]);
    }

    public function testTokenizeMultiply()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('*'), [new MulToken(1)]);
    }

    public function testTokenizeDivide()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('/'), [new DivToken(1)]);
    }

    public function testTokenizeLeftParentheses()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('('), [new LParToken(1)]);
    }

    public function testTokenizeRightParentheses()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize(')'), [new RParToken(1)]);
    }

    public function testTokenizeIgnoreSpaces()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize(' 27  -          19      '), [
            new NumToken(2, '27'), new MinusToken(6), new NumToken(17, '19')
        ]);
    }

    public function testTokenizeComplex()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('12-(    (2  +339)  /7) *      4756098321'),  [
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
            new NumToken(31, '4756098321')
        ]);
    }

}