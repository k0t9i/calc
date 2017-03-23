<?php

namespace app\models;

/**
 * Class Tokenizer
 *
 * Tokenize input string
 *
 * @package app\models
 */
class Tokenizer
{
    public function tokenize($string)
    {
        $result = [];
        $lexemes = $this->parseLexemes($string);
        foreach ($lexemes as $lexeme) {
            $value = $lexeme;
            /** @var Token $proto */
            foreach (Token::getTokenTypes() as $lexemeRegExp => $proto) {
                if ($this->getFirstLexeme($lexeme, $lexemeRegExp) !== false) {
                    $result[] = $proto->create($value);
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
     * @return array List of lexemes from input string
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
     * @return bool|mixed Lexeme or false if lexeme not dound
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
     * @return bool|mixed Lexeme or false if lexeme not found
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
        foreach (Token::getTokenTypes() as $lexemeRegExp => $_) {
            $lexeme = $this->getFirstLexeme($string, $lexemeRegExp);
            if ($lexeme !== false) {
                return $lexeme;
            }
        }
        if ($lengthBefore == strlen($string)) {
            throw new UnknownLexemeException('Unknown lexeme "' . $string[0] . '" at position ' . ($initLength - $lengthBefore + 1));
        }

        return false;
    }
}