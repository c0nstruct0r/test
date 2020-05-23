<?php

namespace common\fixtures;

use common\components\ActiveFixture;
use common\models\PayerAr;

class PayersFixture
    extends ActiveFixture
{
    public $modelClass = PayerAr::class;

    public $depends    = [];
}