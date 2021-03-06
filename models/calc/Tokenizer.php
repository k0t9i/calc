<?php

namespace app\models\calc;

use app\models\calc\interfaces\ITokenizer;
use app\models\calc\tokens\LParToken;
use app\models\calc\tokens\OperatorToken;
use app\models\calc\tokens\Token;

/**
 * Class Tokenizer
 *
 * Tokenize input string
 *
 * @package app\models\calc
 */
class Tokenizer implements ITokenizer
{
    /**
     * Parse expression into list of tokens
     *
     * @param string $expression Input string
     * @return Token[] List of tokens from input string
     */
    public function tokenize($expression)
    {
        $input = $expression;
        $result = [];

        $initLength = strlen($input);
        $prevToken = null;
        while (strlen($input) > 0) {
            $token = $this->searchTokens($input, $initLength);
            if ($token === false) {
                break;
            }

            // If previous token is left parentheses or current token first token of expression
            // then try get unary version of current operator
            if ((is_null($prevToken) || $prevToken instanceof LParToken) && $token instanceof OperatorToken) {
                if (!is_null($unary = $token->getUnaryVersion())) {
                    $token = $unary;
                }
            }

            $result[] = $token;
            $prevToken = $token;
        }

        return $result;
    }

    /**
     * Extracts first lexeme from string and return it, or return false if lexeme not found
     * Changes input string
     *
     * @param string $string Input string
     * @param Token $token Token for search
     * @return bool|Token Lexeme or false if lexeme not found
     */
    public function getFirstLexeme(&$string, Token $token)
    {
        $matches = [];
        if ($token->isFirstLexemeMatched($string, $matches)) {
            $string = $token->removeLexemeFromBeginning($string);
            return $matches[0];
        }

        return false;
    }

    /**
     * Searches all tokens in input string and return first of it, or return false if lexeme not found
     * Changes input string
     *
     * @param string $string Input string
     * @param int $initLength Initial length of string
     * @return bool|Token Token object or false if lexeme not found
     * @throws UnknownLexemeException if found unknown lexeme
     */
    private function searchTokens(&$string, $initLength)
    {
        //Ignore leading spaces
        $string = ltrim($string, ' ');
        if (strlen($string) == 0) {
            return false;
        }

        $lengthBefore = strlen($string);
        $position = $initLength - $lengthBefore + 1;
        foreach (Token::getTokenTypes() as $proto) {
            $lexeme = $this->getFirstLexeme($string, $proto);
            if ($lexeme !== false) {
                return $proto->create($position, $lexeme);
            }
        }
        if ($lengthBefore == strlen($string)) {
            throw new UnknownLexemeException('Unknown lexeme "' . $string[0] . '" at position ' . ($position));
        }

        return false;
    }
}
