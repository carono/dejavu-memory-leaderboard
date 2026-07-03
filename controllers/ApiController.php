<?php

namespace app\controllers;

use app\models\Submission;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * JSON API for receiving benchmark results.
 */
class ApiController extends Controller
{
    /**
     * The API authenticates via payload, not a browser session — CSRF does not apply.
     */
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'submit' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * POST /api/submit — accept a benchmark result as JSON and store it.
     */
    public function actionSubmit(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->getBodyParams();

        $model = new Submission();
        if (!$model->load($data, '')) {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'success' => false,
                'error' => 'Empty or malformed request body. Send a JSON object.',
            ]);
        }

        if (!$model->save()) {
            Yii::$app->response->statusCode = 422;
            return $this->asJson([
                'success' => false,
                'error' => 'Validation failed.',
                'errors' => $model->getErrors(),
            ]);
        }

        // Reload to expose the DB-generated submitted_at value.
        $model->refresh();

        Yii::$app->response->statusCode = 201;
        return $this->asJson([
            'success' => true,
            'id' => $model->id,
            'submitted_at' => $model->submitted_at,
        ]);
    }
}
