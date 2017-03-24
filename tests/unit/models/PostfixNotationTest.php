<?php

namespace tests\models;

use app\models\PostfixNotation;
use app\models\Tokenizer;
use app\models\tokens\LParToken;
use app\models\tokens\MinusToken;
use app\models\tokens\MulToken;
use app\models\tokens\NumToken;
use app\models\tokens\PlusToken;
use app\models\tokens\PowToken;
use Codeception\Test\Unit;

class PostfixNotationTest extends Unit
{
    /**
     * @var Tokenizer
     */
    protected $tokenizer;

    /**
     * @var PostfixNotation
     */
    protected $notation;

    protected function _before()
    {
        $this->tokenizer = new Tokenizer();
        $this->notation = new PostfixNotation();
    }
    public function testConvertEmpty()
    {
        $tokens = $this->tokenizer->tokenize('');
        $this->assertEquals($this->notation->convert($tokens), []);
    }

    public function testConvertAdditionOfTwoNumber()
    {
        $tokens = $this->tokenizer->tokenize('2+3');
        $this->assertEquals($this->notation->convert($tokens), [
            new NumToken(1, '2'), new NumToken(3, '3'), new PlusToken(2)
        ]);
    }

    public function testConvertAdditionOfTwoNumberWithParenthesis()
    {
        $tokens = $this->tokenizer->tokenize('(2+3)');
        $this->assertEquals($this->notation->convert($tokens), [
            new NumToken(2, '2'), new NumToken(4, '3'), new PlusToken(3)
        ]);
    }

    public function testConvertTwoOperator()
    {
        $tokens = $this->tokenizer->tokenize('2+3-1');
        $this->assertEquals($this->notation->convert($tokens), [
            new NumToken(1, '2'), new NumToken(3, '3'), new PlusToken(2), new NumToken(5, '1'), new MinusToken(4)
        ]);
    }

    public function testConvertTwoOperatorWithParentensis()
    {
        $tokens = $this->tokenizer->tokenize('(2+3-1)');
        $this->assertEquals($this->notation->convert($tokens), [
            new NumToken(2, '2'), new NumToken(4, '3'), new PlusToken(3), new NumToken(6, '1'), new MinusToken(5)
        ]);
    }

    public function testConvertMissingRightParentheses()
    {
        $this->expectException(\Exception::class);
        $tokens = $this->tokenizer->tokenize('2+3)*1');
        $this->notation->convert($tokens);
    }

    public function testConvertMissingLeftParentheses()
    {
        $this->expectException(\Exception::class);
        $tokens = $this->tokenizer->tokenize('2+(3*1');
        $this->notation->convert($tokens);
    }

    public function testConvertTwoOperatorWithDifferentPriority()
    {
        $tokens = $this->tokenizer->tokenize('2+3*1');
        $this->assertEquals($this->notation->convert($tokens), [
            new NumToken(1, '2'), new NumToken(3, '3'), new NumToken(5, '1'), new MulToken(4), new PlusToken(2)
        ]);
    }

    public function testConvertTwoOperatorWithDifferentAssociativity()
    {
        $tokens = $this->tokenizer->tokenize('2^3^1');
        $this->assertEquals($this->notation->convert($tokens), [
            new NumToken(1, '2'), new NumToken(3, '3'), new NumToken(5, '1'), new PowToken(4), new PowToken(2)
        ]);
    }
}