<?php

namespace app\controllers;

use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Class CalcController
 *
 * @package app\controllers
 */
class CalcController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'calc' => ['get']
                ],
            ]
        ];
    }

    /**
     * Calculate requested expression
     *
     * @param string $expression Expression to calculate
     * @return array
     * @throws ServerErrorHttpException
     */
    public function actionCalc($expression)
    {
        try {
            return [
                'success' => true,
                'result' => \Yii::$app->calc->calculate($expression)
            ];
        } catch (\Exception $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}
