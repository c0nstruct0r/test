<?php

namespace backend\controllers;

use backend\actions\pays\CurrentMonthAction;
use backend\actions\pays\PayApiIndexAction;
use backend\actions\pays\PayUpdateAction;

class ApiPaysController
    extends RestController
{

    public function actions()
    {
        return [
            'api-index'     => PayApiIndexAction::class,
            'pay-update'    => PayUpdateAction::class,
            'current-month' => CurrentMonthAction::class,
        ];
    }
}
