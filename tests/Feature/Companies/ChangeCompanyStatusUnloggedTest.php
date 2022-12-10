<?php

namespace Tests\Feature\Auth;

use App\Models\Company;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\CompanySeeder;

class ChangeCompanyStatusUnloggedTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_is_deactivate_after_user_logout()
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

        // status after login
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $company->refresh();
        $this->assertEquals(1, $company->active);

        // status after logout
        $this->post('/logout', []);
        $company->refresh();
        $this->assertEquals(0, $company->active);
    }
}
