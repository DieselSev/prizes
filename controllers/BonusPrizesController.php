<?php

namespace app\controllers;

use app\services\bonus\BonusPrizeGettingService;
use app\services\bonus\BonusPrizeRejectionService;
use app\services\bonus\BonusPrizeSendingService;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class BonusPrizesController
 * @package app\controllers
 */
class BonusPrizesController extends BaseController
{
    /**
     * @var BonusPrizeGettingService
     */
    private $bonusPrizeGettingService;

    /**
     * LotteryResultsController constructor.
     * @param $id
     * @param $module
     * @param BonusPrizeGettingService $bonusPrizeGettingService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        BonusPrizeGettingService $bonusPrizeGettingService,
        $config = []
    ) {
        $this->bonusPrizeGettingService = $bonusPrizeGettingService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Send bonus prize to loyalty account
     * @param int $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     * @throws NotFoundHttpException
     */
    public function actionSendToLoyaltyAccount(int $id): Response
    {
        /** @var BonusPrizeSendingService $service */
        $service = Yii::$container->get(BonusPrizeSendingService::class);
        $service->sendToLoyaltyAccount($this->bonusPrizeGettingService->getBonusPrize($id));

        Yii::$app->session->setFlash('success', 'Бонусные баллы перечислены на счет лояльности');
        return $this->redirect('/');
    }

    /**
     * Reject bonus prize
     * @param int $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws NotInstantiableException
     */
    public function actionReject(int $id): Response
    {
        /** @var BonusPrizeRejectionService $service */
        $service = Yii::$container->get(BonusPrizeRejectionService::class);
        $service->rejectPrize($this->bonusPrizeGettingService->getBonusPrize($id));

        Yii::$app->session->setFlash('success', 'Вы отказались от бонусных баллов');
        return $this->redirect('/');
    }
}
