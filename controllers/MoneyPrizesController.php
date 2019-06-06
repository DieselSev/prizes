<?php

namespace app\controllers;

use app\services\money\MoneyPrizeConversionService;
use app\services\money\MoneyPrizeGettingService;
use app\services\money\MoneyPrizeRejectionService;
use app\services\money\MoneyPrizeSendingService;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class MoneyPrizesController
 * @package app\controllers
 */
class MoneyPrizesController extends BaseController
{
    /**
     * @var MoneyPrizeGettingService
     */
    private $moneyPriceGettingService;

    /**
     * LotteryResultsController constructor.
     * @param $id
     * @param $module
     * @param MoneyPrizeGettingService $moneyPrizeGettingService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        MoneyPrizeGettingService $moneyPrizeGettingService,
        $config = []
    ) {
        $this->moneyPriceGettingService = $moneyPrizeGettingService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Send money prize to bank account
     * @param int $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function actionSendToBankAccount(int $id): Response
    {
        /** @var MoneyPrizeSendingService $service */
        $service = Yii::$container->get(MoneyPrizeSendingService::class);

        try {
            $service->sendToBankAccount($this->moneyPriceGettingService->getMoneyPrize($id));
            Yii::$app->session->setFlash('success', 'Денежный приз отправлен на банковский счет');
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect('/');
    }

    /**
     * Convert money prize to bonus prize
     * @param int $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws NotInstantiableException
     */
    public function actionConvertToBonusPrize(int $id): Response
    {
        /** @var MoneyPrizeConversionService $service */
        $service = Yii::$container->get(MoneyPrizeConversionService::class);
        $service->convertToBonusPrize($this->moneyPriceGettingService->getMoneyPrize($id));

        Yii::$app->session->setFlash('success', 'Денежный приз конвертирован в бонусные баллы');
        return $this->redirect('/');
    }

    /**
     * Reject money prize
     * @param int $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws NotInstantiableException
     */
    public function actionReject(int $id): Response
    {
        /** @var MoneyPrizeRejectionService $service */
        $service = Yii::$container->get(MoneyPrizeRejectionService::class);
        $service->rejectPrize($this->moneyPriceGettingService->getMoneyPrize($id));

        Yii::$app->session->setFlash('success', 'Вы отказались от денежного приза');
        return $this->redirect('/');
    }
}
