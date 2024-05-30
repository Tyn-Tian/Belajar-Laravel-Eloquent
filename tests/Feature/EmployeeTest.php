<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testFactory()
    {
        $programmer = Employee::factory()->programmer()->make();
        $programmer->id = "1";
        $programmer->name = "Christian";
        $programmer->save();
        self::assertNotNull(Employee::where('id', '1')->first());

        $seniorProgrammer = Employee::factory()->seniorProgrammer()->create([
            "id" => "2",
            "name" => "Christian"
        ]);
        self::assertNotNull($seniorProgrammer);
        self::assertNotNull(Employee::where('id', '1')->first());
    }
}
