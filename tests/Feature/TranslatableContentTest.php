<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslatableContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_store_and_retrieve_translations(): void
    {
        $category = Category::create([
            'slug' => 'berita',
            'name' => [
                'id' => 'Berita Terkini',
                'en' => 'Latest News',
            ],
            'description' => [
                'id' => 'Kategori untuk berita terbaru.',
                'en' => 'Category for the latest news.',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        app()->setLocale('id');
        $this->assertSame('Berita Terkini', $category->name);
        $this->assertSame('Kategori untuk berita terbaru.', $category->description);

        app()->setLocale('en');
        $this->assertSame('Latest News', $category->name);
        $this->assertSame('Category for the latest news.', $category->description);
    }

    public function test_post_and_page_translations(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'slug' => 'pengumuman-penting',
            'title' => [
                'id' => 'Pengumuman Penting',
                'en' => 'Important Announcement',
            ],
            'summary' => [
                'id' => 'Ringkasan pengumuman.',
                'en' => 'Announcement summary.',
            ],
            'content' => [
                'id' => '<p>Konten bahasa Indonesia</p>',
                'en' => '<p>English content</p>',
            ],
            'status' => PostStatus::Published,
            'published_at' => now(),
        ]);

        app()->setLocale('id');
        $this->assertSame('Pengumuman Penting', $post->title);
        $this->assertSame('<p>Konten bahasa Indonesia</p>', $post->content);

        app()->setLocale('en');
        $this->assertSame('Important Announcement', $post->title);
        $this->assertSame('<p>English content</p>', $post->content);
    }

    public function test_api_returns_translations_based_on_locale(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'slug' => 'layanan',
            'name' => [
                'id' => 'Layanan Publik',
                'en' => 'Public Services',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'slug' => 'jadwal-layanan',
            'title' => [
                'id' => 'Jadwal Layanan Publik',
                'en' => 'Public Service Schedule',
            ],
            'summary' => [
                'id' => 'Ringkasan jadwal',
                'en' => 'Schedule summary',
            ],
            'content' => [
                'id' => '<p>Isi jadwal ID</p>',
                'en' => '<p>Content schedule EN</p>',
            ],
            'status' => PostStatus::Published,
            'published_at' => now(),
        ]);

        // 1. Request with Accept-Language: id
        $responseId = $this->withHeader('Accept-Language', 'id')
            ->getJson('/api/v1/posts/jadwal-layanan');

        $responseId->assertOk()
            ->assertJsonPath('data.title', 'Jadwal Layanan Publik')
            ->assertJsonPath('data.summary', 'Ringkasan jadwal');

        // 2. Request with query param ?lang=en
        $responseEn = $this->getJson('/api/v1/posts/jadwal-layanan?lang=en');

        $responseEn->assertOk()
            ->assertJsonPath('data.title', 'Public Service Schedule')
            ->assertJsonPath('data.summary', 'Schedule summary');
    }
}
