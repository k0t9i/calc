<?php

namespace app\models;

class Tokenizer
{
    const TOKEN_NUMBER = '[0-9]+';
    const TOKEN_PLUS = '\+';
    const TOKEN_MINUS = '\-';
    const TOKEN_MULTIPLY = '\*';
    const TOKEN_DIVIDE = '\/';
    const TOKEN_LEFT_PARENTHESIS = '\(';
    const TOKEN_RIGHT_PARENTHESIS = '\)';

    private static $_tokens = [
        self::TOKEN_NUMBER,
        self::TOKEN_PLUS,
        self::TOKEN_MINUS,
        self::TOKEN_MULTIPLY,
        self::TOKEN_DIVIDE,
        self::TOKEN_LEFT_PARENTHESIS,
        self::TOKEN_RIGHT_PARENTHESIS
    ];

    public function parse($string)
    {
        $input = $string;
        $result = [];

        $initLength = strlen($input);
        if (!empty($input)) {
            while (!empty($input)) {
                if (substr($input, 0, 1) === ' ') {
                    $input = substr($input, 1);
                    continue;
                }
                $lengthBefore = strlen($input);
                foreach (self::$_tokens as $token) {
                    $token = '/^' . $token . '/';
                    $matches = [];
                    if (preg_match($token, $input, $matches)) {
                        $result[] = $matches[0];
                        $input = preg_replace($token, '', $input);
                        break;
                    }
                }
                if ($lengthBefore == strlen($input)) {
                    throw new UnknownTokenException('Unknown token "' . $input[0] . '" at position ' . ($initLength - $lengthBefore + 1));
                }
            }
        }

        return $result;
    }
}