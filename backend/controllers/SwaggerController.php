<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

class SwaggerController extends Controller
{
    public function actions()
    {

         return [
            'doc' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => Url::to(['swagger/api'], true),
            ],

            'api' => [
                'class' => 'light\swagger\SwaggerApiAction',
                'scanDir' => [
                    Yii::getAlias('@backend/controllers'),
                    Yii::getAlias('@backend/models'),
                ],
            ],
        ];
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => YII_ENV_DEV,   // Только для разработки
                        'actions' => ['doc', 'api'],
                    ],
                ],
            ],
        ];
    }
}