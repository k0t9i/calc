<?php

class CalcCest
{
    public function calculateExpression(FunctionalTester $I)
    {
        $I->wantTo('Calculate expression');
        $expressions = ['12+3', '77*10', 'sin (PI/2)', '1-(2+3)/4*5'];

        foreach ($expressions as $expression) {
            $I->sendGET('calc/calc', [
                'expression' => $expression
            ]);
            $I->seeResponseIsJson();
            $I->seeResponseCodeIs(200);
            $I->seeResponseMatchesJsonType($this->getCalcAnswerJsonType());
            $I->canSeeResponseContainsJson([
                'result' => Yii::$app->calc->calculate($expression),
                'success' => true
            ]);
        }
    }

    public function sendRequestWithoutExpression(FunctionalTester $I)
    {
        $I->wantTo('Send request without expression');
        $I->sendGET('calc/calc');
        $I->seeResponseCodeIs(400);
    }

    public function sendInvalidExpression(FunctionalTester $I)
    {
        $I->wantTo('Send invalid expression');
        $I->sendGET('calc/calc', [
            'expression' => '('
        ]);
        $I->seeResponseCodeIs(500);
    }

    public function sendPost(FunctionalTester $I)
    {
        $I->wantTo('Send post');
        $I->sendPOST('calc/calc', [
            'expression' => '1+2'
        ]);
        $I->seeResponseCodeIs(405);
    }


    private function getCalcAnswerJsonType()
    {
        return [
            'result' => 'float|integer',
            'success' => 'boolean'
        ];
    }
}