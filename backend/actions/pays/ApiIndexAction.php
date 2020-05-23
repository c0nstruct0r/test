<?php

namespace backend\actions\pays;

use app\domain\pays\repositories\ars\PayAr;
use app\domain\pays\services\PayService;
use backend\actions\pays\params\PaysGetParams;
use backend\controllers\ApiPaysController;
use http\Exception\BadMethodCallException;
use Yii;
use yii\rest\Action;

/**
 * Class ApiIndexAction
 *
 * @package backend\actions\pays
 */
class ApiIndexAction
    extends Action
{
    public $controller;

    public $modelClass = PayAr::class;

    private $service;

    public function __construct(
        string $id,
        ApiPaysController $controller,
        PayService $service,
        array $config = []
    )
    {
        parent::__construct($id, $controller, $config);
        $this->service = $service;
    }

    public function run()
    {
        if (!Yii::$app->request->isGet) {
            throw new BadMethodCallException('Only get required');
        }

        $get = Yii::$app->request->get();
        $params = PaysGetParams::fromGetToOrdersRequest($get);

        $result = $this->service->find($params);

        if ($params->s2) {
            $results = array_map(function ($pay) {
                return [
                    'id'   => $pay->id,
                    'text' => sprintf('%s %s %s', $pay->cost, $pay->op_date_processed ?? '', $pay->comment),
                ];
            }, $result->pays);

            return
                [
                    'results' => $results,
                ];
        }

        return
            [
                'result'  => 'success',
                'message' => null,
                'count'   => $result->count,
                'data'    => $result->pays,
            ];
    }
}
