<?php

namespace app\models\calc\interfaces;

/**
 * Interface ITokenizer
 *
 * @package app\models\calc\interfaces
 */
interface ITokenizer
{
    /**
     * Parse expression into list of tokens
     *
     * @param $expression
     * @return array
     */
    public function tokenize($expression);
}
