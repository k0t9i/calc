<?php

namespace app\components;

use app\models\calc\interfaces\ICalculator;
use yii\base\Component;

/**
 * Class Calculator
 *
 * @package app\components
 */
class Calculator extends Component
{
    /**
     * @var ICalculator
     */
    private $calc;

    /**
     * Calculator constructor.
     *
     * @param ICalculator $calculator
     * @param array $config
     */
    public function __construct(ICalculator $calculator, array $config = [])
    {
        $this->calc = $calculator;
        parent::__construct($config);
    }

    /**
     * Wrapper for concrete implementations of ICalculator interface
     *
     * @param $expression
     * @return float Result of expression
     */
    public function calculate($expression)
    {
        return $this->calc->calculate($expression);
    }
}
