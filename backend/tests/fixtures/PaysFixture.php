<?php

namespace common\fixtures;

use app\domain\pays\repositories\ars\PayAr;
use common\components\ActiveFixture;

class PaysFixture
    extends ActiveFixture
{
    public $modelClass = PayAr::class;

    public $depends = [];
}