<?php

namespace Tests\Feature\Auth;

use App\Models\Company;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\CompanySeeder;

class ChangeCompanyStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_is_activate_after_user_login()
    {
        // prepare User and Company
        $user = User::factory()->create();
        $this->seed(
            CompanySeeder::class
        );
        $company = Company::first();

        // assign Company to User
        $user->company_id = $company->id;
        $user->save();

        // status before login
        $this->assertEquals(0, $company->active);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // status after login
        $company->refresh();
        $this->assertEquals(1, $company->active);
    }
}
