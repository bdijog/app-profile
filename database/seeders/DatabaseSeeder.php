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
                'name' => [
                    'id' => 'Berita Utama',
                    'en' => 'Main News',
                ],
                'description' => [
                    'id' => 'Berita terkini seputar kegiatan dan pengumuman instansi.',
                    'en' => 'Latest news regarding agency activities and announcements.',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $catLayanan = Category::firstOrCreate(
            ['slug' => 'info-layanan'],
            [
                'name' => [
                    'id' => 'Informasi Layanan',
                    'en' => 'Service Information',
                ],
                'description' => [
                    'id' => 'Panduan dan alur pelayanan untuk masyarakat.',
                    'en' => 'Guides and service flow for the public.',
                ],
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
                'title' => [
                    'id' => 'Sosialisasi Pelayanan Digital Masyarakat Berbasis Mobile',
                    'en' => 'Socialization of Mobile-Based Public Digital Services',
                ],
                'summary' => [
                    'id' => 'Pemerintah meluncurkan portal layanan mobile untuk mempermudah akses masyarakat dalam menyampaikan aspirasi dan pengaduan.',
                    'en' => 'The government launches a mobile service portal to facilitate public access in conveying aspirations and complaints.',
                ],
                'content' => [
                    'id' => '<p>Dalam rangka meningkatkan mutu pelayanan publik, instansi kami meluncurkan aplikasi portal digital. Aplikasi ini memungkinkan masyarakat menyampaikan pertanyaan, pengaduan, dan memantau status secara langsung.</p>',
                    'en' => '<p>In order to improve the quality of public services, our agency has launched a digital portal application. This application allows the public to submit questions, complaints, and monitor their status directly.</p>',
                ],
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
                'title' => [
                    'id' => 'Tentang Kami',
                    'en' => 'About Us',
                ],
                'content' => [
                    'id' => '<h2>Profil Instansi</h2><p>Kami berkomitmen memberikan pelayanan terbaik bagi seluruh lapisan masyarakat secara transparan, akuntabel, dan inovatif.</p>',
                    'en' => '<h2>Agency Profile</h2><p>We are committed to providing the best service for all levels of society in a transparent, accountable, and innovative manner.</p>',
                ],
                'status' => PageStatus::Published,
                'sort_order' => 1,
                'show_in_menu' => true,
            ]
        );

        // 6. Sample Slide
        Slide::firstOrCreate(
            ['link_url' => '/tentang-kami'],
            [
                'title' => [
                    'id' => 'Selamat Datang di Portal Layanan Publik',
                    'en' => 'Welcome to the Public Services Portal',
                ],
                'description' => [
                    'id' => 'Ajukan pertanyaan dan dapatkan informasi terpercaya seputar pelayanan kami.',
                    'en' => 'Submit inquiries and obtain trusted information regarding our services.',
                ],
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
                'name' => [
                    'id' => 'Pelayanan Umum',
                    'en' => 'General Services',
                ],
                'description' => [
                    'id' => 'Pertanyaan seputar syarat, biaya, dan prosedur layanan.',
                    'en' => 'Questions regarding requirements, costs, and service procedures.',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $topicAdministrasi = QuestionTopic::firstOrCreate(
            ['slug' => 'administrasi-kependudukan'],
            [
                'name' => [
                    'id' => 'Administrasi Kependudukan',
                    'en' => 'Civil Administration',
                ],
                'description' => [
                    'id' => 'Pertanyaan terkait berkas dan surat keterangan.',
                    'en' => 'Questions regarding documents and certificates.',
                ],
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 8. Sample FAQ
        Faq::firstOrCreate(
            ['sort_order' => 1],
            [
                'topic_id' => $topicPelayanan->id,
                'question' => [
                    'id' => 'Berapa lama proses tanggapan terhadap pertanyaan masyarakat?',
                    'en' => 'How long does it take to respond to public inquiries?',
                ],
                'answer' => [
                    'id' => '<p>Tim admin akan meninjau dan menjawab pertanyaan Anda maksimal 1x24 jam kerja.</p>',
                    'en' => '<p>The admin team will review and answer your question within 1x24 working hours maximum.</p>',
                ],
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
                'name' => [
                    'id' => 'Menu Utama Header',
                    'en' => 'Main Header Menu',
                ],
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'sort_order' => 1],
            [
                'label' => [
                    'id' => 'Beranda',
                    'en' => 'Home',
                ],
                'url' => '/',
                'type' => MenuItemType::Custom,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'sort_order' => 2],
            [
                'label' => [
                    'id' => 'Tentang Kami',
                    'en' => 'About Us',
                ],
                'url' => '/pages/tentang-kami',
                'type' => MenuItemType::Page,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'sort_order' => 3],
            [
                'label' => [
                    'id' => 'Berita',
                    'en' => 'News',
                ],
                'url' => '/posts',
                'type' => MenuItemType::Custom,
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'sort_order' => 4],
            [
                'label' => [
                    'id' => 'Tanya Masyarakat',
                    'en' => 'Public Inquiries',
                ],
                'url' => '/pertanyaan',
                'type' => MenuItemType::Custom,
                'sort_order' => 4,
                'is_active' => true,
            ]
        );
    }
}
