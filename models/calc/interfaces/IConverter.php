<?php

namespace app\models\calc\interfaces;

/**
 * Interface IConverter
 *
 * @package app\models\calc\interfaces
 */
interface IConverter
{
    /**
     * Convert expression in infix notation to array of tokens in postfix
     *
     * @param $expression
     * @return array
     */
    public function convert($expression);
}
