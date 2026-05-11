<?php

namespace Tests\Feature;

use App\Models\LoginHistory;
use App\Models\User;
use Database\Seeders\RestaurantPosAllInOneSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard_and_json_requests_get_unauthenticated_response(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));

        $this->getJson(route('admin.dashboard'))
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_login_page_accepts_lang_query_and_persists_khmer_locale_for_guests(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $response = $this->get(route('admin.login', ['lang' => 'km']));

        $response->assertOk();
        $response->assertSeeText('ចូលគណនីរបស់អ្នក');
        $response->assertSeeText('ចូលគណនី');
        $this->assertSame('km', session('locale'));
    }

    public function test_user_can_log_in_with_username_and_login_history_is_recorded(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $beforeCount = DB::table('login_histories')->where('username', 'manager.main')->count();

        $response = $this->post(route('admin.login.attempt'), [
            'login' => 'manager.main',
            'password' => 'manager@123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->userByUsername('manager.main'));
        $this->assertSame($beforeCount + 1, DB::table('login_histories')->where('username', 'manager.main')->count());

        $latestHistory = LoginHistory::query()->where('username', 'manager.main')->latest('id')->first();

        $this->assertNotNull($latestHistory);
        $this->assertTrue((bool) $latestHistory->success);
        $this->assertNull($latestHistory->failure_reason);
        $this->assertNull($latestHistory->logged_out_at);
    }

    public function test_user_can_log_in_with_email_address(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $response = $this->post(route('admin.login.attempt'), [
            'login' => 'manager@restaurant.local',
            'password' => 'manager@123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->userByUsername('manager.main'));
    }

    public function test_invalid_login_keeps_user_guest_and_records_failure(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $beforeCount = DB::table('login_histories')->where('username', 'manager.main')->count();

        $response = $this->from(route('admin.login'))
            ->post(route('admin.login.attempt'), [
                'login' => 'manager.main',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->assertSame($beforeCount + 1, DB::table('login_histories')->where('username', 'manager.main')->count());

        $latestHistory = LoginHistory::query()->where('username', 'manager.main')->latest('id')->first();

        $this->assertNotNull($latestHistory);
        $this->assertFalse((bool) $latestHistory->success);
        $this->assertSame('Invalid credentials', $latestHistory->failure_reason);
    }

    public function test_logout_invalidates_session_and_marks_latest_open_login_history(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $user = $this->userByUsername('manager.main');
        $history = LoginHistory::create([
            'user_id' => $user->id,
            'username' => $user->username,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'success' => true,
            'logged_in_at' => now(),
            'logged_out_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();

        $history->refresh();
        $this->assertNotNull($history->logged_out_at);
    }

    private function userByUsername(string $username): User
    {
        return User::query()->where('username', $username)->firstOrFail();
    }
}
