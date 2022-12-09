<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\CompanySeeder;
use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;


class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_company_succesfull_read()
    {
        // this test depends on previous companies seeding
        $this->seed(
            CompanySeeder::class
        );


        $company = Company::where('active', 1)->first();
        $this->assertEquals(1, $company->id);
        $this->assertEquals('PuertaLogic', $company->name);
        $this->assertEquals(1, $company->active);
    }
}
