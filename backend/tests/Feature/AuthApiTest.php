<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders(['Origin' => 'http://localhost:5173', 'Referer' => 'http://localhost:5173/']);
    }

    public function test_citizen_registration_hashes_password_and_ignores_requested_role(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Asha Citizen', 'email' => 'asha@example.test',
            'password' => 'Citizenship123', 'password_confirmation' => 'Citizenship123', 'role' => 'admin',
        ]);

        $response->assertCreated()->assertJsonPath('user.role', 'citizen');
        $user = User::where('email', 'asha@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('Citizenship123', $user->password));
        $this->assertNotSame('Citizenship123', $user->password);
        $this->assertAuthenticatedAs($user);
    }

    public function test_citizen_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create(['email' => 'citizen@example.test', 'password' => 'Citizenship123']);
        $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => 'Citizenship123'])
            ->assertOk()->assertJsonPath('user.id', $user->id);
        $this->assertAuthenticatedAs($user);
        $this->postJson('/api/auth/logout')->assertOk();
        $this->getJson('/api/auth/user')->assertUnauthorized();
    }

    public function test_admin_login_is_separate_from_citizen_login(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.test', 'password' => 'Administrator123', 'role' => 'admin']);
        $this->postJson('/api/admin/auth/login', ['email' => $admin->email, 'password' => 'Administrator123'])
            ->assertOk()->assertJsonPath('user.role', 'admin');
        $this->assertAuthenticatedAs($admin);
        $this->postJson('/api/auth/login', ['email' => $admin->email, 'password' => 'Administrator123'])
            ->assertUnprocessable();
    }

    public function test_password_policy_is_enforced_and_reset_email_is_sent(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'reset@example.test']);
        $this->postJson('/api/auth/register', [
            'name' => 'Weak Password', 'email' => 'weak@example.test', 'password' => 'weak', 'password_confirmation' => 'weak',
        ])->assertUnprocessable();

        $this->postJson('/api/auth/forgot-password', ['email' => $user->email])->assertOk();
        Notification::assertSentTo($user, ResetPassword::class);
    }
}
