<?php

namespace backend\actions\pays;

use app\domain\pays\repositories\ars\PayAr;
use backend\controllers\PaysController;
use common\models\search\PayOperationSearch;
use http\Exception\BadMethodCallException;
use Yii;
use yii\rest\Action;

class IndexAction
    extends Action
{
    public $controller;

    public $modelClass = PayAr::class;

    public function __construct(
        string $id,
        PaysController $controller,
        array $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    public function run()
    {
        if (!Yii::$app->request->isGet) {
            throw new BadMethodCallException('Only get required');
        }

        $searchModel = new PayOperationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}
