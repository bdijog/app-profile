<?php

namespace Database\Seeders;

use App\Enums\AnswerStatus;
use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Enums\UserRole;
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
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ]
        );

        // 2. Settings
        $defaultSettings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Dinas Layanan Terpadu', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Melayani dengan Cepat, Tepat, dan Transparan', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Portal resmi profil instansi dan layanan aduan masyarakat terpadu.', 'type' => 'textarea'],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'kontak@instansi.go.id', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '(021) 1234-5678', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_whatsapp', 'value' => '081234567890', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_address', 'value' => 'Jl. Merdeka No. 10, Jakarta Pusat', 'type' => 'textarea'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Category
        $catBerita = Category::firstOrCreate(
            ['slug' => 'berita-utama'],
            [
                'name' => 'Berita Utama',
                'description' => 'Berita terkini seputar kegiatan dan pengumuman instansi.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $catLayanan = Category::firstOrCreate(
            ['slug' => 'info-layanan'],
            [
                'name' => 'Informasi Layanan',
                'description' => 'Panduan dan alur pelayanan untuk masyarakat.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 4. Sample Post
        Post::firstOrCreate(
            ['slug' => 'sosialisasi-pelayanan-digital-masyarakat'],
            [
                'user_id' => $admin->id,
                'category_id' => $catBerita->id,
                'title' => 'Sosialisasi Pelayanan Digital Masyarakat Berbasis Mobile',
                'summary' => 'Pemerintah meluncurkan portal layanan mobile untuk mempermudah akses masyarakat dalam menyampaikan aspirasi dan pengaduan.',
                'content' => '<p>Dalam rangka meningkatkan mutu pelayanan publik, instansi kami meluncurkan aplikasi portal digital. Aplikasi ini memungkinkan masyarakat menyampaikan pertanyaan, pengaduan, dan memantau status secara langsung.</p>',
                'status' => PostStatus::Published,
                'published_at' => now(),
                'is_featured' => true,
                'view_count' => 125,
            ]
        );

        // 5. Sample Page
        Page::firstOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'user_id' => $admin->id,
                'title' => 'Tentang Kami',
                'content' => '<h2>Profil Instansi</h2><p>Kami berkomitmen memberikan pelayanan terbaik bagi seluruh lapisan masyarakat secara transparan, akuntabel, dan inovatif.</p>',
                'status' => PageStatus::Published,
                'sort_order' => 1,
                'show_in_menu' => true,
            ]
        );

        // 6. Sample Slide
        Slide::firstOrCreate(
            ['title' => 'Selamat Datang di Portal Layanan Publik'],
            [
                'description' => 'Ajukan pertanyaan dan dapatkan informasi terpercaya seputar pelayanan kami.',
                'image' => 'slides/default-banner.jpg',
                'link_url' => '/tentang-kami',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now()->toDateString(),
            ]
        );

        // 7. Question Topics
        $topicPelayanan = QuestionTopic::firstOrCreate(
            ['slug' => 'pelayanan-umum'],
            [
                'name' => 'Pelayanan Umum',
                'description' => 'Pertanyaan seputar syarat, biaya, dan prosedur layanan.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $topicAdministrasi = QuestionTopic::firstOrCreate(
            ['slug' => 'administrasi-kependudukan'],
            [
                'name' => 'Administrasi Kependudukan',
                'description' => 'Pertanyaan terkait berkas dan surat keterangan.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 8. Sample FAQ
        Faq::firstOrCreate(
            ['question' => 'Berapa lama proses tanggapan terhadap pertanyaan masyarakat?'],
            [
                'topic_id' => $topicPelayanan->id,
                'answer' => '<p>Tim admin akan meninjau dan menjawab pertanyaan Anda maksimal 1x24 jam kerja.</p>',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 9. Sample Question & Answer
        $question = Question::firstOrCreate(
            ['subject' => 'Jam operasional layanan tatap muka'],
            [
                'topic_id' => $topicPelayanan->id,
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081298765432',
                'body' => 'Selamat pagi, mohon informasi jam buka loket pelayanan tatap muka untuk hari kerja dan hari Sabtu?',
                'status' => QuestionStatus::Answered,
                'ip_address' => '127.0.0.1',
            ]
        );

        QuestionAnswer::firstOrCreate(
            ['question_id' => $question->id],
            [
                'user_id' => $admin->id,
                'body' => '<p>Selamat pagi Bapak Budi. Loket pelayanan kami buka setiap hari Senin - Jumat pukul 08.00 - 15.00 WIB. Hari Sabtu, Minggu, dan hari libur nasional tutup. Terima kasih.</p>',
                'status' => AnswerStatus::Published,
                'is_published' => true,
                'answered_at' => now(),
            ]
        );

        // 10. Sample Navigation Menu
        $menuHeader = Menu::firstOrCreate(
            ['location' => MenuLocation::Header->value],
            [
                'name' => 'Menu Utama Header',
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'label' => 'Beranda'],
            [
                'url' => '/',
                'type' => MenuItemType::Custom,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'label' => 'Tentang Kami'],
            [
                'url' => '/pages/tentang-kami',
                'type' => MenuItemType::Page,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'label' => 'Berita'],
            [
                'url' => '/posts',
                'type' => MenuItemType::Custom,
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'label' => 'Tanya Masyarakat'],
            [
                'url' => '/pertanyaan',
                'type' => MenuItemType::Custom,
                'sort_order' => 4,
                'is_active' => true,
            ]
        );
    }
}
