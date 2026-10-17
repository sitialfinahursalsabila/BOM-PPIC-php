<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** LKM 3: skenario authentication */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_halaman_login_dapat_dibuka(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk ke SI BOM');
    }

    public function test_login_benar_berhasil(): void
    {
        $this->post('/login', ['username' => 'budi.produksi', 'password' => 'password123'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs(Pengguna::find('USR02'));
    }

    public function test_password_salah_ditolak(): void
    {
        $this->post('/login', ['username' => 'budi.produksi', 'password' => 'salah'])
            ->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_halaman_protected_tanpa_login_ditolak(): void
    {
        $this->get('/status-produksi')->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_logout_mengakhiri_session(): void
    {
        $this->actingAs(Pengguna::find('USR02'))
            ->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
