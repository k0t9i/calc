<?php

namespace tests\models;

use app\models\Tokenizer;
use app\models\UnknownTokenException;
use Codeception\Test\Unit;

class TokenizerTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Tokenizer
     */
    protected $tokenizer;

    protected function _before()
    {
        $this->tokenizer = new Tokenizer();
    }

    public function testEmpty()
    {
        $this->assertTrue($this->tokenizer->parse('') === []);
    }

    public function testOnlyOneSpace()
    {
        $this->assertTrue($this->tokenizer->parse(' ') === []);
    }

    public function testSpaceDivideToken()
    {
        $this->assertTrue($this->tokenizer->parse('1 2') === ['1', '2']);
    }
    
    public function testConst()
    {
        $this->assertTrue($this->tokenizer->parse('1') === ['1']);
    }

    public function testLongConst()
    {
        $this->assertTrue($this->tokenizer->parse('123') === ['123']);
    }

    public function testSimplePlus()
    {
        $this->assertTrue($this->tokenizer->parse('1+2') === ['1', '+', '2']);
    }

    public function testPlus()
    {
        $this->assertTrue($this->tokenizer->parse('132+17') === ['132', '+', '17']);
    }

    public function testUnknownToken()
    {
        $this->expectException(UnknownTokenException::class);
        $this->tokenizer->parse('13~+17');
    }

    public function testMinus()
    {
        $this->assertTrue($this->tokenizer->parse('554-1024') === ['554', '-', '1024']);
    }

    public function testMultiply()
    {
        $this->assertTrue($this->tokenizer->parse('337*123') === ['337', '*', '123']);
    }

    public function testDivide()
    {
        $this->assertTrue($this->tokenizer->parse('654/19') === ['654', '/', '19']);
    }

    public function testParenthesis()
    {
        $this->assertTrue($this->tokenizer->parse('(23-(33))') === ['(', '23', '-', '(', '33', ')', ')']);
    }

    public function testIgnoreSpaces()
    {
        $this->assertTrue($this->tokenizer->parse(' 27  -          19      ') === ['27', '-', '19']);
    }

    public function testUnknownTokenPosition3()
    {
        $this->expectExceptionMessage('position 3');
        $this->tokenizer->parse('13~+17');
    }

    public function testUnknownTokenPosition5()
    {
        $this->expectExceptionMessage('position 5');
        $this->tokenizer->parse('1343~+17');
    }

    public function testUnknownTokenValue()
    {
        $this->expectExceptionMessage('token "~"');
        $this->tokenizer->parse('13~+17');
    }
    public function testLiteral()
    {
        $this->assertTrue($this->tokenizer->parse('sin(12) + 1') === ['sin', '(', '12', ')', '+', '1']);
    }

    public function testComplex()
    {
        $this->assertTrue($this->tokenizer->parse('12-(    (2  +339)  /7) *      4756098321') === ['12', '-', '(', '(', '2', '+', '339', ')', '/', '7', ')', '*', '4756098321']);
    }
}