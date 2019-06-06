<?php

namespace app\commands;

use app\services\money\MoneyPrizeSendingService;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\httpclient\Exception;

/**
 * Class MoneyPrizeSendingController
 * @package app\commands
 */
class MoneyPrizeSendingController extends Controller
{
    /**
     * @var MoneyPrizeSendingService
     */
    private $moneyPriceSendingService;

    /**
     * LotteryResultsController constructor.
     * @param $id
     * @param $module
     * @param MoneyPrizeSendingService $moneyPriceSendingService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        MoneyPrizeSendingService $moneyPriceSendingService,
        $config = []
    ) {
        $this->moneyPriceSendingService = $moneyPriceSendingService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return void
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionIndex(): void
    {
        $result = $this->moneyPriceSendingService->sendAllToBankAccount();
        $this->stdout('Result: ' . ($result ? 'ok' : 'error'));
    }
}
