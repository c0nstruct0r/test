<?php

namespace backend\actions\pays;

use app\domain\pays\repositories\ars\PayAr;
use app\domain\pays\services\PayService;
use backend\controllers\ApiPaysController;
use yii\rest\Action;

/**
 * Class CurrentMonthAction
 *
 * @package backend\actions\pays
 */
class CurrentMonthAction
    extends Action
{
    public $controller;

    public $modelClass = PayAr::class;

    private PayService $payService;

    public function __construct(
        string $id,
        ApiPaysController $controller,
        PayService $payService,
        array $config = []
    )
    {
        parent::__construct($id, $controller, $config);
        $this->payService = $payService;
    }

    public function run()
    {
        $count = $this->payService->countIncompleteForCurrentMonth();

        return [
            'count' => $count,
        ];
    }
}
