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
    const TOKEN_NUMBER = '[0-9]+';
    const TOKEN_PLUS = '\+';
    const TOKEN_MINUS = '\-';
    const TOKEN_MULTIPLY = '\*';
    const TOKEN_DIVIDE = '\/';
    const TOKEN_LEFT_PARENTHESIS = '\(';
    const TOKEN_RIGHT_PARENTHESIS = '\)';

    /**
     * List of existing tokens
     *
     * @var array
     */
    private static $_tokens = [
        self::TOKEN_NUMBER,
        self::TOKEN_PLUS,
        self::TOKEN_MINUS,
        self::TOKEN_MULTIPLY,
        self::TOKEN_DIVIDE,
        self::TOKEN_LEFT_PARENTHESIS,
        self::TOKEN_RIGHT_PARENTHESIS
    ];

    /**
     * Parse input string into list of tokens
     *
     * @param string $string Input string
     * @return array List of tokens from input string
     */
    public function parse($string)
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
     * Extracts first token from string and return it, or return false if token not found
     * Changes input string
     *
     * @param string $string Input string
     * @param string $tokenRegExp Token regular expression
     * @return bool|mixed Token or false if token not dound
     */
    public function getFirstToken(&$string, $tokenRegExp)
    {
        $tokenRegExp = '/^' . $tokenRegExp . '/';
        $matches = [];
        if (preg_match($tokenRegExp, $string, $matches)) {
            $string = preg_replace($tokenRegExp, '', $string);
            return $matches[0];
        }

        return false;
    }

    /**
     * Searches all tokens in input string and return first of it, or return false if token not found
     * Changes input string
     *
     * @param string $string Input string
     * @param int $initLength Initial length of string
     * @return bool|mixed Token or false if token not found
     * @throws UnknownTokenException if found unknown token
     */
    private function searchTokens(&$string, $initLength)
    {
        //Ignore leading spaces
        $string = ltrim($string, ' ');
        if (strlen($string) == 0) {
            return false;
        }

        $lengthBefore = strlen($string);
        foreach (self::$_tokens as $tokenRegExp) {
            $token = $this->getFirstToken($string, $tokenRegExp);
            if ($token !== false) {
                return $token;
            }
        }
        if ($lengthBefore == strlen($string)) {
            throw new UnknownTokenException('Unknown token "' . $string[0] . '" at position ' . ($initLength - $lengthBefore + 1));
        }
        
        return false;
    }
}