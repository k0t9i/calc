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
        $result = [];
        $lexemes = $this->parseLexemes($string);
        foreach ($lexemes as $lexeme) {
            $value = $lexeme->getValue();
            foreach (Token::getTokenTypes() as $lexemeRegExp => $proto) {
                if ($this->getFirstLexeme($value, $lexemeRegExp) !== false) {
                    $result[] = $proto->create($lexeme->getPosition(), $lexeme->getValue());
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Parse input string into list of lexemes
     *
     * @param string $string Input string
     * @return Lexeme[] List of lexemes from input string
     */
    public function parseLexemes($string)
    {
        $input = $string;
        $result = [];

        $initLength = strlen($input);
        while (strlen($input) > 0) {
            $lexeme = $this->searchLexemes($input, $initLength);
            if ($lexeme === false) {
                break;
            }
            $result[] = $lexeme;
        }

        return $result;
    }

    /**
     * Extracts first lexeme from string and return it, or return false if lexeme not found
     * Changes input string
     *
     * @param string $string Input string
     * @param string $lexemeRegExp Lexeme regular expression
     * @return bool|string Lexeme or false if lexeme not dound
     */
    public function getFirstLexeme(&$string, $lexemeRegExp)
    {
        $lexemeRegExp = '/^' . $lexemeRegExp . '/i';
        $matches = [];
        if (preg_match($lexemeRegExp, $string, $matches) && $matches[0]) {
            $string = preg_replace($lexemeRegExp, '', $string, 1);
            return $matches[0];
        }

        return false;
    }

    /**
     * Searches all lexemes in input string and return first of it, or return false if lexeme not found
     * Changes input string
     *
     * @param string $string Input string
     * @param int $initLength Initial length of string
     * @return bool|Lexeme Lexeme object or false if lexeme not found
     * @throws UnknownLexemeException if found unknown lexeme
     */
    private function searchLexemes(&$string, $initLength)
    {
        //Ignore leading spaces
        $string = ltrim($string, ' ');
        if (strlen($string) == 0) {
            return false;
        }

        $lengthBefore = strlen($string);
        $position = $initLength - $lengthBefore + 1;
        foreach (Token::getTokenTypes() as $lexemeRegExp => $_) {
            $lexeme = $this->getFirstLexeme($string, $lexemeRegExp);
            if ($lexeme !== false) {
                return new Lexeme($lexeme, $position);
            }
        }
        if ($lengthBefore == strlen($string)) {
            throw new UnknownLexemeException('Unknown lexeme "' . $string[0] . '" at position ' . ($position));
        }

        return false;
    }
}