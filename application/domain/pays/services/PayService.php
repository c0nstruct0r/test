<?php

namespace app\domain\pays\services;

use app\domain\common\utils\Pages;
use app\domain\pays\dtos\CommentDto;
use app\domain\pays\models\Pay;
use app\domain\pays\repositories\ars\PayAr;
use app\domain\pays\repositories\PayRepository;
use app\domain\pays\services\dtos\PaysRequest;
use app\domain\pays\services\dtos\PaysResponse;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

class PayService
{
    private PayRepository $repository;

    public function __construct(PayRepository $payRepository)
    {
        $this->repository = $payRepository;
    }

    public function find(PaysRequest $request): PaysResponse
    {
        $response = new PaysResponse();
        $pages = Pages::from($request);
        $response->pays = $this->repository->findMany($this->condition($request), $pages->offset, $pages->limit);
        $response->count = $this->repository->countMany($this->condition($request));

        return $response;
    }

    private function condition(PaysRequest $request)
    {
        return function (ActiveQuery $query) use ($request) {

            $query = $this->filterLikeBySearch($query, $request->query);
            $query = $this->filterByIsLinked($query, $request->isLinked);
            $query = $this->filterByType($query, $request->type);

            $desc = $request->ascending ? '' : ' desc';
            $query->orderBy($request->orderBy . $desc);
            $sql = $query->createCommand()->getRawSql();
        };
    }

    public function filterLikeBySearch(ActiveQuery $query, string $string)
    {
        $query->filterWhere(['like', "CONCAT_WS(' ',op_date_processed, cost, comment, order_id)", $string]);

        return $query;
    }

    public function filterByIsLinked(ActiveQuery $query, ?bool $isLinked): ActiveQuery
    {
        switch (true) {
            case $isLinked === true:
                $query->andWhere(['is not', 'order_id', null]);
                break;
            case $isLinked === false:
                $query->andWhere(['is', 'order_id', null]);
                break;

            default:
                break;
        }

        return $query;
    }

    public function filterByType(ActiveQuery $query, ?bool $type): ActiveQuery
    {
        switch (true) {
            case $type === true:
                $query->andWhere(['>', 'cost', 0]);
                break;
            case $type === false:
                $query->andWhere(['<', 'cost', 0]);
                break;

            default:
                break;
        }

        return $query;
    }

    /**
     * @param int $limit
     *
     * @return Pay[]
     * @throws InvalidConfigException
     */
    public function findWithoutPayer(int $limit = 100): array
    {
        $pays = $this->repository->findMany(function (ActiveQuery $query) use ($limit) {
            $query->where([PayAr::PAYER_ID => null]);
            $query->orderBy('id desc');
        }, 0, $limit);

        return $pays;
    }

    final public function findAllByOrderId(int $orderId): array
    {
        $pays = $this->repository->findManyByOrderId($orderId);

        return $pays;
    }

    final public function findAllByCost(?int $cost): array
    {
        $pays = $this->repository->findManyByCost($cost);

        return $pays;
    }

    final public function findAllByOrderIds(array $orderIds): array
    {
        $pays = $this->repository->findManyByOrderIds($orderIds);

        return $pays;
    }

    final public function getOneById(int $id): Pay
    {
        return $this->repository->getOneById($id);
    }

    final public function countIncompleteForCurrentMonth(): int
    {
        return $this->repository->countIncompleteForCurrentMonth();
    }

    public function setLink(?int $payId, ?string $entity, ?int $id): ?Pay
    {
        if (!$payId || !$entity || !$id) {
            return null;
        }

        $pay = $this->getOneByIdOrNull($payId);
        if ($pay) {
            $pay->opLink = $entity;
            $pay->orderId = $id;
            $pay = $this->updateOne($pay);
        }

        return $pay;
    }

    final public function getOneByIdOrNull(?int $id): ?Pay
    {
        return $this->repository->getOneByIdOrNull($id);
    }

    final public function updateOne(Pay $pay): Pay
    {
        return $this->repository->updateOne($pay);
    }

    final public function getOneBySalaryId(?int $salaryId): ?Pay
    {
        $pay = $this->repository->getOneBySalaryId($salaryId);

        return $pay;
    }

    final public function getOneByPurchasesId(?int $entityId): ?Pay
    {
        $pay = $this->repository->getOneByPurchasesId($entityId);

        return $pay;
    }

    final public function getPayIds(?int $id): array
    {
        $pays = $this->repository->findMany(function (ActiveQuery $query) use ($id) {
            $query->where([
                              PayAr::ORDER_ID => $id,
                              PayAr::OP_LINK  => 'order',
                          ]);
        });

        $ids = array_reduce($pays, function ($accumulator, $item) {
            if ($item->id) {
                $accumulator[] = $item->id;
            }

            return $accumulator;
        });
        if (is_null($ids)) {
            return [];
        }
        $ids = array_values($ids);

        return $ids;
    }

    public function parseComment(?string $comment): ?CommentDto
    {
        if (!$comment) {
            return null;
        }

        $result = new CommentDto;
        $comment = explode(' ', $comment);
        if (count($comment) < 6) {
            return null;
        }
        if (in_array($comment[0], ['C2C'])) {
            return null;
        }

        $result->middleName = array_pop($comment);
        $result->firstName = array_pop($comment);
        $result->firstLetter = array_pop($comment);

        return $result;
    }

}