<?php

namespace app\domain\pays\repositories;

use app\domain\common\persistence\BaseRepository;
use app\domain\pays\models\Pay;
use app\domain\pays\repositories\ars\PayAr;
use yii\db\ActiveQuery;

class PayRepository
    extends BaseRepository
{
    final public function __construct()
    {
        parent::__construct(PayAr::class, PayAr::MAPPING, Pay::class);
    }

    final public function findManyByOrderId(int $orderId): array
    {
        $pays = parent::findMany(function (ActiveQuery $query) use ($orderId) {
            $query->where([
                              PayAr::OP_LINK  => 'order',
                              PayAr::ORDER_ID => $orderId,
                          ]);
//            $sql = $query->createCommand()->getRawSql();
        });

        return $pays;
    }

    final public function findManyByCost(?int $cost): array
    {
        $pays = parent::findMany(function (ActiveQuery $query) use ($cost) {
            $query
                ->where([PayAr::COST => $cost])
                ->orderBy('op_date_processed desc');
        }, 0, 10);

        return $pays;
    }

    final public function findManyByOrderIds(array $orderIds): array
    {
        $pays = parent::findMany(function (ActiveQuery $query) use ($orderIds) {
            $query->where([PayAr::OP_LINK => 'order'])
                  ->andWhere(['in', PayAr::ORDER_ID, $orderIds]);
        });

        return $pays;
    }

    public function getOneById(int $id): Pay
    {
        return $this->findOne(function (ActiveQuery $query) use ($id) {
            $query->where([PayAr::ID => $id]);
        });
    }

    public function getOneByIdOrNull(?int $id): ?Pay
    {
        return $this->findOneOrNull(function (ActiveQuery $query) use ($id) {
            $query->where([PayAr::ID => $id]);
        });
    }

    public function countIncompleteForCurrentMonth(): int
    {
        $result = $this->countMany(function (ActiveQuery $query) {
            $query->where('MONTH(`op_date_processed`)=MONTH(CURRENT_DATE()) and YEAR(`op_date_processed`)=YEAR(CURRENT_DATE()) and order_id is null');
        });

        return (int)$result;
    }


    public function getOneBySalaryId(?int $entityId): ?Pay
    {
        $pay = $this->findOneOrNull(function (ActiveQuery $query) use ($entityId) {
            $query->where([
                              PayAr::ORDER_ID => $entityId,
                              PayAr::OP_LINK  => PayAr::PAY_LINK_SALARY,
                          ]);
        });

        return $pay;
    }

    public function getOneByPurchasesId(?int $entityId): ?Pay
    {
        $pay = $this->findOneOrNull(function (ActiveQuery $query) use ($entityId) {
            $query->where([
                              PayAr::ORDER_ID => $entityId,
                              PayAr::OP_LINK  => PayAr::PAY_LINK_PURCHASES,
                          ]);
        });

        return $pay;
    }
}