<?php

namespace app\controllers;

use app\services\subject\SubjectPrizeGettingService;
use app\services\subject\SubjectPrizeRejectionService;
use app\services\subject\SubjectPrizeSendingService;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SubjectPrizesController
 * @package app\controllers
 */
class SubjectPrizesController extends BaseController
{
    /**
     * @var SubjectPrizeGettingService
     */
    private $subjectPrizeGettingService;

    /**
     * LotteryResultsController constructor.
     * @param $id
     * @param $module
     * @param SubjectPrizeGettingService $subjectPrizeGettingService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        SubjectPrizeGettingService $subjectPrizeGettingService,
        $config = []
    ) {
        $this->subjectPrizeGettingService = $subjectPrizeGettingService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Send subject prize to post
     * @param int $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws NotInstantiableException
     */
    public function actionSendToPost(int $id): Response
    {
        /** @var SubjectPrizeSendingService $service */
        $service = Yii::$container->get(SubjectPrizeSendingService::class);
        $service->sendToPost($this->subjectPrizeGettingService->getSubjectPrize($id));

        Yii::$app->session->setFlash('success', 'Приз будет отправлен Вам по почте');
        return $this->redirect('/');
    }

    /**
     * Reject subject prize
     * @param int $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     * @throws NotFoundHttpException
     */
    public function actionReject(int $id): Response
    {
        /** @var SubjectPrizeRejectionService $service */
        $service = Yii::$container->get(SubjectPrizeRejectionService::class);
        $service->rejectPrize($this->subjectPrizeGettingService->getSubjectPrize($id));

        Yii::$app->session->setFlash('success', 'Вы отказались от приза');
        return $this->redirect('/');
    }
}
