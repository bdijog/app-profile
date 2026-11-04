<?php

namespace Tests\Feature;

use App\Enums\AnswerStatus;
use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Berita',
            'slug' => 'berita',
            'description' => 'Kategori Berita',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Judul Artikel Pertama',
            'slug' => 'judul-artikel-pertama',
            'summary' => 'Ringkasan artikel pertama.',
            'content' => '<p>Isi artikel lengkap.</p>',
            'status' => PostStatus::Published,
            'published_at' => now(),
            'is_featured' => true,
        ]);

        Page::create([
            'user_id' => $user->id,
            'title' => 'Tentang Kami',
            'slug' => 'tentang-kami',
            'content' => '<p>Konten halaman tentang kami.</p>',
            'status' => PageStatus::Published,
            'sort_order' => 1,
            'show_in_menu' => true,
        ]);

        Slide::create([
            'title' => 'Slide Selamat Datang',
            'description' => 'Deskripsi slide.',
            'image' => 'slides/sample.jpg',
            'link_url' => '/tentang-kami',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'name' => 'Menu Header',
            'location' => MenuLocation::Header,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => 'Beranda',
            'url' => '/',
            'type' => MenuItemType::Custom,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Setting::set('site_name', 'Portal Instansi');

        $topic = QuestionTopic::create([
            'name' => 'Pelayanan',
            'slug' => 'pelayanan',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Faq::create([
            'topic_id' => $topic->id,
            'question' => 'Bagaimana cara mengajukan pertanyaan?',
            'answer' => '<p>Melalui formulir online pada menu tanya.</p>',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $question = Question::create([
            'topic_id' => $topic->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'subject' => 'Pertanyaan Pelayanan',
            'body' => 'Kapan jam layanan loket dibuka?',
            'status' => QuestionStatus::Answered,
        ]);

        QuestionAnswer::create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'body' => '<p>Buka pukul 08:00 WIB.</p>',
            'status' => AnswerStatus::Published,
            'is_published' => true,
            'answered_at' => now(),
        ]);
    }

    public function test_can_fetch_categories(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'description'],
                ],
            ]);
    }

    public function test_can_fetch_published_posts(): void
    {
        $response = $this->getJson('/api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'summary', 'content', 'status'],
                ],
            ]);
    }

    public function test_can_fetch_post_detail(): void
    {
        $response = $this->getJson('/api/v1/posts/judul-artikel-pertama');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'judul-artikel-pertama');
    }

    public function test_can_fetch_page_detail(): void
    {
        $response = $this->getJson('/api/v1/pages/tentang-kami');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'tentang-kami');
    }

    public function test_can_fetch_active_slides(): void
    {
        $response = $this->getJson('/api/v1/slides');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'image'],
                ],
            ]);
    }

    public function test_can_fetch_menu_by_location(): void
    {
        $response = $this->getJson('/api/v1/menus/header');

        $response->assertStatus(200)
            ->assertJsonPath('data.location', 'header')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'location',
                    'items' => [
                        '*' => ['id', 'label', 'url', 'type'],
                    ],
                ],
            ]);
    }

    public function test_can_fetch_settings(): void
    {
        $response = $this->getJson('/api/v1/settings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'site_name',
                ],
            ]);
    }

    public function test_can_fetch_question_topics(): void
    {
        $response = $this->getJson('/api/v1/question-topics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug'],
                ],
            ]);
    }

    public function test_can_fetch_answered_questions(): void
    {
        $response = $this->getJson('/api/v1/questions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'subject',
                        'body',
                        'status',
                        'answers',
                    ],
                ],
            ]);
    }

    public function test_public_can_submit_inquiry(): void
    {
        $topic = QuestionTopic::first();

        $response = $this->postJson('/api/v1/questions', [
            'topic_id' => $topic->id,
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@example.com',
            'phone' => '08123456789',
            'subject' => 'Permohonan informasi perizinan',
            'body' => 'Mohon petunjuk berkas apa saja yang harus disiapkan untuk izin usaha kuliner?',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.subject', 'Permohonan informasi perizinan');

        $this->assertDatabaseHas('questions', [
            'email' => 'ahmad@example.com',
            'subject' => 'Permohonan informasi perizinan',
            'status' => 'pending',
        ]);
    }

    public function test_can_fetch_faqs(): void
    {
        $response = $this->getJson('/api/v1/faqs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'question', 'answer'],
                ],
            ]);
    }
}
