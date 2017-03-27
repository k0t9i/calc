<?php

namespace app\models\calc\interfaces;

/**
 * Interface ICalculator
 *
 * @package app\models\calc\interfaces
 */
interface ICalculator
{
    /**
     * Calculate expression
     *
     * @param $expression
     * @return float Result of expressions
     */
    public function calculate($expression);
}
