<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use BezhanSalleh\LanguageSwitch\Http\Livewire\LanguageSwitchComponent;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentLanguageSwitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_switch_is_configured_with_supported_locales(): void
    {
        $switch = LanguageSwitch::make();

        $this->assertEquals(['id', 'en'], $switch->getLocales());
        $this->assertEquals('Bahasa Indonesia', $switch->getLabel('id'));
        $this->assertEquals('English', $switch->getLabel('en'));
    }

    public function test_language_switch_component_can_change_locale(): void
    {
        Livewire::test(LanguageSwitchComponent::class)
            ->call('changeLocale', 'en')
            ->assertRedirect();

        $this->assertEquals('en', session('locale'));

        Livewire::test(LanguageSwitchComponent::class)
            ->call('changeLocale', 'id')
            ->assertRedirect();

        $this->assertEquals('id', session('locale'));
    }

    public function test_admin_login_page_displays_language_switch(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSeeLivewire(LanguageSwitchComponent::class);
    }

    public function test_admin_dashboard_displays_language_switch(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSeeLivewire(LanguageSwitchComponent::class);
    }
}
