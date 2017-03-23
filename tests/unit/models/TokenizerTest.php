<?php

namespace tests\models;

use app\models\DivToken;
use app\models\LParToken;
use app\models\MinusToken;
use app\models\MulToken;
use app\models\NumToken;
use app\models\PlusToken;
use app\models\RParToken;
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
        $this->assertTrue($this->tokenizer->parseLexemes('') === []);
    }

    public function testParseLexemesOnlyOneSpace()
    {
        $this->assertTrue($this->tokenizer->parseLexemes(' ') === []);
    }
    
    public function testParseLexemesNumber()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('1') === ['1']);
    }

    public function testParseLexemesLongNumber()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('123') === ['123']);
    }

    public function testParseLexemesFloatNumber()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('13333.24444') === ['13333.24444']);
    }

    public function testParseLexemesSpaceDivideLexeme()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('1 2') === ['1', '2']);
    }

    public function testParseLexemesSimplePlus()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('1+2') === ['1', '+', '2']);
    }

    public function testParseLexemesPlus()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('132+17') === ['132', '+', '17']);
    }

    public function testParseLexemesUnknownLexeme()
    {
        $this->expectException(UnknownLexemeException::class);
        $this->tokenizer->parseLexemes('13~+17');
    }

    public function testParseLexemesMinus()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('554-1024') === ['554', '-', '1024']);
    }

    public function testParseLexemesMultiply()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('337*123') === ['337', '*', '123']);
    }

    public function testParseLexemesDivide()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('654/19') === ['654', '/', '19']);
    }

    public function testParseLexemesParenthesis()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('(23-(33))') === ['(', '23', '-', '(', '33', ')', ')']);
    }

    public function testParseLexemesIgnoreSpaces()
    {
        $this->assertTrue($this->tokenizer->parseLexemes(' 27  -          19      ') === ['27', '-', '19']);
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

    public function testParseLexemesLiteral()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('sin(12) + 1') === ['sin', '(', '12', ')', '+', '1']);
    }

    public function testParseLexemesLiteralDifferentCases()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('AbCdE') === ['AbCdE']);
    }

    public function testParseLexemesComplex()
    {
        $this->assertTrue($this->tokenizer->parseLexemes('12-(    (2  +339)  /7) *      4756098321') === ['12', '-', '(', '(', '2', '+', '339', ')', '/', '7', ')', '*', '4756098321']);
    }

    public function testTokenizeEmpty()
    {
        $this->assertTrue($this->tokenizer->tokenize('') === []);
    }

    public function testTokenizeOnlyOneSpace()
    {
        $this->assertTrue($this->tokenizer->tokenize(' ') === []);
    }

    public function testTokenizeNumber()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('123'), [new NumToken('123')]);
    }

    public function testTokenizeFloatNumber()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('123.23423'), [new NumToken('123.23423')]);
    }

    public function testTokenizeSpaceDivideLexeme()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('1 2 3'), [new NumToken('1'), new NumToken(2), new NumToken(3)]);
    }

    public function testTokenizePlus()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('+'), [new PlusToken()]);
    }

    public function testTokenizeMinus()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('-'), [new MinusToken()]);
    }

    public function testTokenizeMultiply()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('*'), [new MulToken()]);
    }

    public function testTokenizeDivide()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('/'), [new DivToken()]);
    }

    public function testTokenizeLeftParentheses()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('('), [new LParToken()]);
    }

    public function testTokenizeRightParentheses()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize(')'), [new RParToken()]);
    }

    public function testTokenizeIgnoreSpaces()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize(' 27  -          19      '), [new NumToken(27), new MinusToken(), new NumToken(19)]);
    }

    public function testTokenizeComplex()
    {
        $this->assertEqualArrayOfObject($this->tokenizer->tokenize('12-(    (2  +339)  /7) *      4756098321'),  [
            new NumToken(12),
            new MinusToken(),
            new LParToken(),
            new LParToken(),
            new NumToken(2),
            new PlusToken(),
            new NumToken(339),
            new RParToken(),
            new DivToken(),
            new NumToken(7),
            new RParToken(),
            new MulToken(),
            new NumToken(4756098321)
        ]);
    }

}