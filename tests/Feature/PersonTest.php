<?php

namespace Tests\Feature;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Chris";
        $person->last_name = "Tian";
        $person->save();

        self::assertEquals("CHRIS Tian", $person->full_name);

        $person->full_name = "Joko Morro";
        $person->save();

        self::assertEquals("JOKO", $person->first_name);
        self::assertEquals("Morro", $person->last_name);
    }

    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = "Chris";
        $person->last_name = "Tian";
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }
}