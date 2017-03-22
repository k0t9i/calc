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
    const LEXEME_NUMBER = '[0-9]+';
    const LEXEME_LITERAL = '[a-z][a-z0-9]*';
    const LEXEME_PLUS = '\+';
    const LEXEME_MINUS = '\-';
    const LEXEME_MULTIPLY = '\*';
    const LEXEME_DIVIDE = '\/';
    const LEXEME_LEFT_PARENTHESIS = '\(';
    const LEXEME_RIGHT_PARENTHESIS = '\)';

    /**
     * List of existing lexemes
     *
     * @var array
     */
    private static $_lexemes = [
        self::LEXEME_NUMBER,
        self::LEXEME_LITERAL,
        self::LEXEME_PLUS,
        self::LEXEME_MINUS,
        self::LEXEME_MULTIPLY,
        self::LEXEME_DIVIDE,
        self::LEXEME_LEFT_PARENTHESIS,
        self::LEXEME_RIGHT_PARENTHESIS
    ];

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
        if (preg_match($lexemeRegExp, $string, $matches)) {
            $string = preg_replace($lexemeRegExp, '', $string);
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
        foreach (self::$_lexemes as $lexemeRegExp) {
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