<?php

namespace app\domain\pays\services;

use app\domain\payers\services\PayerService;
use app\domain\payers\utils\Payers;

class PayManager
{
    private PayService $payService;
    private PayerService $payerService;

    public function __construct(PayService $payService, PayerService $payerService)
    {
        $this->payService = $payService;
        $this->payerService = $payerService;
    }

    public function getPayerFromPay()
    {
        $pays = $this->payService->findWithoutPayer(1000);
        foreach ($pays as $pay) {
            $commentDto = $this->payService->parseComment($pay->comment);
            if ($commentDto) {
                echo sprintf("%s %s %s\n", $pay->op_date_processed, $pay->cost, $pay->comment);
                $name = sprintf('%s %s %s', $commentDto->firstName, $commentDto->middleName, $commentDto->firstLetter);
                $payer = $this->payerService->getOneByNameOrNull($name);
                $pay->payer = $name;
                if (!$payer) {
                    $payer = Payers::from($name);
                    $this->payerService->createOne($payer);
                }

                $pay->payerId = $payer->id;
                $this->payService->updateOne($pay);
            }
        }

        return;
    }
}