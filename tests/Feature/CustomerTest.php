<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testOneToOne()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::find("TYN");
        self::assertNotNull($customer);

        $wallet = Wallet::where("customer_id", "=", "TYN")->first();
        self::assertNotNull($wallet);

        self::assertEquals(1000000, $wallet->amount);
    }

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = "TYN";
        $customer->name = "Christian";
        $customer->email = "test@gmail.com";
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;

        $customer->wallets()->save($wallet);
        self::assertNotNull($wallet->customer_id);
    }
}
