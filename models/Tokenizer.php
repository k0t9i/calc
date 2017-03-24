<?php

namespace app\models;
use app\models\tokens\Token;

/**
 * Class Tokenizer
 *
 * Tokenize input string
 *
 * @package app\models
 */
class Tokenizer
{
    /**
     * Parse input string into list of tokens
     *
     * @param string $string Input string
     * @return Token[] List of tokens from input string
     */
    public function tokenize($string)
    {
        $input = $string;
        $result = [];

        $initLength = strlen($input);
        while (strlen($input) > 0) {
            $token = $this->searchTokens($input, $initLength);
            if ($token === false) {
                break;
            }
            $result[] = $token;
        }

        return $result;
    }

    /**
     * Extracts first lexeme from string and return it, or return false if lexeme not found
     * Changes input string
     *
     * @param string $string Input string
     * @param string $lexemeRegExp Lexeme regular expression
     * @return bool|string Lexeme or false if lexeme not found
     */
    public function getFirstLexeme(&$string, $lexemeRegExp)
    {
        $matches = [];
        if (preg_match($lexemeRegExp, $string, $matches) && $matches[0]) {
            $string = preg_replace($lexemeRegExp, '', $string, 1);
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
        foreach (Token::getTokenTypes() as $lexemeRegExp => $proto) {
            $lexeme = $this->getFirstLexeme($string, $lexemeRegExp);
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