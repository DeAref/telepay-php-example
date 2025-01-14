<?php

namespace Test\Transfer;

use Test\TestInit;
use TelePay\TelePayException;
use PHPUnit\Framework\TestCase;
use TelePay\TelePayWithdrawInput;

class WithdrawTest extends TestCase
{
    private $asset = "TON";
    private $blockchain = "TON";
    private $network = "testnet";
    private $amount = 1;
    private $message = "for my savings account";

    public function getWallet()
    {
        $wallet = getenv("WHITDRAW_TO_WALLET")  ?: "WHITDRAW_TO_WALLET";
        return $wallet;
    }

    public function testWithdrawFeeSuccessfull()
    {
        $telepay = TestInit::client();

        $withdraw = new TelePayWithdrawInput($this->asset, $this->blockchain, $this->network,  $this->amount, $this->getWallet());
        $withdraw->setMessage($this->message);

        $respWithdrawFee = $telepay->getWithdrawFee($withdraw);

        $this->assertNotNull($respWithdrawFee);
        $this->assertArrayHasKey('blockchain_fee', $respWithdrawFee);
        $this->assertNotNull($respWithdrawFee['blockchain_fee']);
        $this->assertArrayHasKey('processing_fee', $respWithdrawFee);
        $this->assertNotNull($respWithdrawFee['processing_fee']);
        $this->assertArrayHasKey('total', $respWithdrawFee);
        $this->assertNotNull($respWithdrawFee['total']);
    }

    public function testWithdrawFeeFail()
    {
        $telepay = TestInit::client();

        $unexistentAsset = "UNEXISTENT";

        $withdraw = new TelePayWithdrawInput($unexistentAsset, $this->blockchain, $this->network, $this->amount, $this->getWallet());
        $withdraw->setMessage($this->message);

        try {
            $telepay->getWithdrawFee($withdraw);
            $this->fail('TelePayException was not thrown');
        } catch (TelePayException $exception) {
            $this->assertEquals(401, $exception->getStatusCode());
            $this->assertEquals('INVALID_ASSET_BLOCKCHAIN_NETWORK_COMBINATION', $exception->getError());
        }
    }

    public function testWithdrawSuccessfull()
    {
        $telepay = TestInit::client();

        $withdraw = new TelePayWithdrawInput($this->asset, $this->blockchain, $this->network,  $this->amount, $this->getWallet());
        $withdraw->setMessage($this->message);

        $respWithdraw = $telepay->withdraw($withdraw);

        $this->assertNotNull($respWithdraw);
        $this->assertEquals('pending', $respWithdraw['status']);
    }

    public function testWithdrawFail()
    {
        $telepay = TestInit::client();

        $unexistentAsset = "UNEXISTENT";

        $withdraw = new TelePayWithdrawInput($unexistentAsset, $this->blockchain, $this->network, $this->amount, $this->getWallet());
        $withdraw->setMessage($this->message);

        try {
            $telepay->withdraw($withdraw);
            $this->fail('TelePayException was not thrown');
        } catch (TelePayException $exception) {
            $this->assertEquals(401, $exception->getStatusCode());
            $this->assertEquals('INVALID_ASSET_BLOCKCHAIN_NETWORK_COMBINATION', $exception->getError());
        }
    }
}
