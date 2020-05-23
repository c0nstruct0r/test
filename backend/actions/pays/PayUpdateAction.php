<?php

namespace backend\actions\pays;

use backend\controllers\ApiPaysController;
use common\models\PayOperation;
use http\Exception\BadMethodCallException;
use Yii;
use yii\rest\Action;

class PayUpdateAction
    extends Action
{
    public $controller;

    public $modelClass = PayOperation::class;

    public function __construct(
        string $id,
        ApiPaysController $controller,
        array $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    final public function run(): array
    {
        if (!Yii::$app->request->isPost) {
            throw new BadMethodCallException('Only post required');
        }

        $model = $this->findModel(Yii::$app->request->post('editableKey'));

        @$link = Yii::$app->request->post(substr($model::className(), strrpos($model::className(), '\\') + 1))
                 [Yii::$app->request->post('editableIndex')]['op_link'];
        if (!empty($link)) {
            $model->op_link = $link;
            $output = $model->linkName;
        }

        @$comment = Yii::$app->request->post(substr($model::className(), strrpos($model::className(), '\\') + 1))
                    [Yii::$app->request->post('editableIndex')]['comment'];
        if (!empty($comment)) {
            $model->comment = $comment;
            $output = $model->comment;
        }

        if ($model->save()) {
            return ['output' => $output, 'success'];
        } else {
            return ['output' => $output, 'error'];
        }

        return ['ok' => false];
    }
}
