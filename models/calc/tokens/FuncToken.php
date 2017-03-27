<?php

namespace app\models\calc\tokens;

/**
 * Class FuncToken
 * Base class for all functions
 *
 * @package app\models\calc\tokens
 */
abstract class FuncToken extends OperatorToken
{
    /**
     * @inheritdoc
     */
    public function argsCount()
    {
        return 1;
    }

    /**
     * @inheritdoc
     */
    protected function requireValue()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPrecedence()
    {
        return self::PRECEDENCE_HIGH;
    }

    /**
     * @inheritdoc
     */
    public function getAssociativity()
    {
        return self::ASSOCIATIVE_RIGHT;
    }

    /**
     * Lexeme regular expression for function Token
     * After function token should be space or left parentheses
     *
     * @return string
     */
    public function getLexemeRegExp()
    {
        $regExp = '([' . implode('][', str_split($this->getLexeme())) . '])';
        return $regExp . '([ \(])';
    }

    /**
     * Remove token lexeme from beginning of the expression
     * After function token should be space or left parentheses
     *
     * @param $expression
     * @return mixed
     */
    public function removeLexemeFromBeginning($expression)
    {
        return preg_replace($this->getLexemeFullRegExp(), '$2', $expression, 1);
    }
}
