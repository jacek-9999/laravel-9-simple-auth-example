<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\CompanySeeder;
use App\Models\Company;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();
        $this->seed(
            CompanySeeder::class
        );
        $company = Company::first();
        // assign Company to User
        $user->company_id = $company->id;
        $user->save();

        $this->assertTrue($user->active);
        $this->assertTrue($user->verified);
        $this->assertTrue($user->hasActiveCompany());

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_user_inactive()
    {
        $user = User::factory()->create();
        $this->seed(
            CompanySeeder::class
        );
        $company = Company::first();
        $user->company_id = $company->id;
        // deactivate user
        $user->active = false;
        $user->save();

        $this->assertFalse($user->active);
        $this->assertTrue($user->verified);
        $this->assertTrue($user->hasActiveCompany());

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_user_unverified()
    {
        $user = User::factory()->create();
        $this->seed(
            CompanySeeder::class
        );
        $company = Company::first();
        $user->company_id = $company->id;
        // making user unverified here
        $user->verified = false;
        $user->save();

        $this->assertTrue($user->active);
        $this->assertFalse($user->verified);
        $this->assertTrue($user->hasActiveCompany());

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_user_has_no_active_company()
    {
        $user = User::factory()->create();
        $this->seed(
            CompanySeeder::class
        );
        $user->company_id = null;
        $user->save();
        $this->assertTrue($user->active);
        $this->assertTrue($user->verified);
        $this->assertFalse($user->hasActiveCompany());

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }
}
