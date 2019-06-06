<?php
namespace app\services\money;

use app\models\MoneyPrize;
use RuntimeException;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 * Class MoneyPrizeSendingService
 * @package app\services\money
 */
class MoneyPrizeSendingService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * MoneyPrizeSendingService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param MoneyPrize $prize
     * @return void
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function sendToBankAccount(MoneyPrize $prize): void
    {
        /** @var Response $response */
        $response = $this->client
            ->createRequest()
            ->setUrl($prize->user->bank_account_url)
            ->setData(['amount' => $prize->amount])
            ->send();

        if (!$response->getIsOk()) {
            throw new RuntimeException('Не удалось отправить деньги на счет');
        }

        $prize->sent_to_bank_account = true;
        $prize->save();
    }

    /**
     * @return bool
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function sendAllToBankAccount(): bool
    {
        $prizes = MoneyPrize::find()
            ->where([
                'sent_to_bank_account'     => false,
                'converted_to_bonus_prize' => false,
                'is_rejected'              => false,
            ])
            ->limit(10)
            ->all();

        $result = true;
        foreach ($prizes as $prize) {
            try {
                $this->sendToBankAccount($prize);
            } catch (RuntimeException $exception) {
                $result = false;
            }
        }

        return $result;
    }
}