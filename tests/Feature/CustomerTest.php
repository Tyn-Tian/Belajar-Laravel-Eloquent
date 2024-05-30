<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\VirtualAccountSeeder;
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

        $customer->wallet()->save($wallet);
        self::assertNotNull($wallet->customer_id);
    }

    public function testHasOneThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find("TYN");
        self::assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        self::assertNotNull($virtualAccount);
        self::assertEquals("BCA", $virtualAccount->bank);
    }

    public function testManyToMany()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::find("TYN");
        self::assertNotNull($customer);
        
        $customer->likeProducts()->attach("1");
        $products = $customer->likeProducts;
        self::assertCount(1, $products);

        self::assertEquals("1", $products[0]->id);
    }

    public function testManyToManyDetach()
    {
        $this->testManyToMany();

        $customer = Customer::find("TYN");
        $customer->likeProducts()->detach("1");

        $products = $customer->likeProducts;
        self::assertCount(0, $products);
    }

    public function testPivotAttribute()
    {
        $this->testManyToMany();

        $customer = Customer::find("TYN");
        $products = $customer->likeProducts;

        foreach ($products as  $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }

    public function testPivotAttributeCondition()
    {
        $this->testManyToMany();

        $customer = Customer::find("TYN");
        $products = $customer->likeProductsLastWeek;

        foreach ($products as  $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }

    public function testPivotModel()
    {
        $this->testManyToMany();

        $customer = Customer::find("TYN");
        $products = $customer->likeProducts;

        foreach ($products as $product){
            $pivot = $product->pivot; // object Model Like
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);

            self::assertNotNull($pivot->customer);

            self::assertNotNull($pivot->product);
        }
    }

    public function testOneToOnePolymorphic()
    {
        $this->seed([CustomerSeeder::class, ImageSeeder::class]);

        $customer = Customer::find("TYN");
        self::assertNotNull($customer);

        $image = $customer->image;
        self::assertNotNull($image);

        self::assertEquals("https://www.programmerzamannow.com/image/1.jpg", $image->url);
    }

    public function testEager() 
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);

        $customer = Customer::with(["wallet", "image"])->find("TYN");
        self::assertNotNull($customer);
    }

    public function testEagerModel()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);

        $customer = Customer::find("TYN");
        self::assertNotNull($customer);
    }
}
