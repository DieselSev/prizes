<?php

namespace app\controllers;

use app\services\lottery\RandomPriceCreationService;
use app\services\lottery\UserPrizesGettingService;
use Exception;
use Yii;
use yii\web\ErrorAction;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends BaseController
{
    /**
     * @var UserPrizesGettingService
     */
    private $userPricesGettingService;

    /**
     * @var RandomPriceCreationService
     */
    private $randomPriceCreationService;

    /**
     * LotteryResultsController constructor.
     * @param $id
     * @param $module
     * @param UserPrizesGettingService $userPricesGettingService
     * @param RandomPriceCreationService $randomPriceCreationService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        UserPrizesGettingService $userPricesGettingService,
        RandomPriceCreationService $randomPriceCreationService,
        $config = []
    ) {
        $this->userPricesGettingService = $userPricesGettingService;
        $this->randomPriceCreationService = $randomPriceCreationService;

        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $prizes = $this->userPricesGettingService->getCurrentUserPrizesForActiveLottery();

        if (Yii::$app->request->isPost && !$prizes) {
            $prizes[] = $this->randomPriceCreationService->createPrize();
        }

        return $this->render('index', ['prizes' => $prizes]);
    }
}
