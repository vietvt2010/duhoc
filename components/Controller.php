<?php

namespace app\components;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Base application controller extend from yii\web\Controller
 * @author vietvt <vietvotrung@admicro.vn>
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     * @return AccesRule
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'controllers' => ['app', 'auth'],
                        'allow' => true,
                    ],
                    [
                        'roles' => ['@'],
                        'allow' => true,
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 
     * @return type
     */
    public function isConfirmedPost() {
        return Yii::$app->request->isPost && Yii::$app->request->post('_confirm', 0);
    }
    
    /**
     * Response error exception
     * @param type $message
     * @param type $code
     * @throws \yii\base\ErrorException
     */
    public function responseErrorException($message, $code)
    {
        $exception = new \yii\base\ErrorException($message, $code);
        Yii::$app->errorLog->logException($exception);

        throw $exception;
    }

    /**
     * Response no permission to perform acction
     * @throws \yii\web\ForbiddenHttpException
     */
    public function responseNoPermission()
    {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app.global', 'You don\'t have permission to perform this action.'), 403);
    }

    /**
     * Response json data
     * @param string|array $data
     */
    public function responseJSON($data)
    {
        if (function_exists('ob_gzhandler'))
            ob_start('ob_gzhandler');
        else
            ob_start();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $data;

        ob_end_flush();

        Yii::$app->end();
    }

    /**
     * Response Bad request exception
     * @param type $message
     * @param type $code
     * @throws \yii\web\BadRequestHttpException
     */
    public function responseBadRequest($message = null, $code = 400)
    {
        if(empty($message)) {
            $message = Yii::t('app.global', 'You request is not valid.');
        }

        throw new \yii\web\BadRequestHttpException($message, $code);
    }
}
