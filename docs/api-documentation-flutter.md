# 📱 Panduan Integrasi REST API (Flutter Developer)

Base URL: `http://<domain-atau-ip>:8000/api/v1`

Header standar untuk setiap request:
```http
Accept: application/json
Content-Type: application/json
```

---

## 1. Modul CMS

### a. Daftar Artikel / Berita
- **Endpoint**: `GET /api/v1/posts`
- **Query Params**:
  - `page` (int, default: 1)
  - `per_page` (int, default: 10, max: 50)
  - `category` (string slug atau int id) — contoh: `?category=berita-utama`
  - `search` (string) — contoh: `?search=pelayanan`
- **Response Contoh (200)**:
```json
{
  "data": [
    {
      "id": 1,
      "title": "Sosialisasi Pelayanan Digital Masyarakat",
      "slug": "sosialisasi-pelayanan-digital-masyarakat",
      "summary": "Ringkasan singkat artikel...",
      "content": "<p>Konten HTML lengkap...</p>",
      "image": "http://localhost:8000/storage/posts/example.jpg",
      "status": "published",
      "is_featured": true,
      "view_count": 125,
      "published_at": "2026-11-04T02:24:21.000000Z",
      "category": {
        "id": 1,
        "name": "Berita Utama",
        "slug": "berita-utama"
      },
      "author": {
        "id": 1,
        "name": "Administrator",
        "avatar": null
      }
    }
  ],
  "links": { ... },
  "meta": { "current_page": 1, "last_page": 5, "total": 45 }
}
```

### b. Artikel Unggulan (Headline / Carousel)
- **Endpoint**: `GET /api/v1/posts/featured`
- **Query Params**: `limit` (int, default: 5)
- **Response**: Array artikel dengan status `is_featured: true`.

### c. Detail Artikel
- **Endpoint**: `GET /api/v1/posts/{slug}`
- **Keterangan**: Setiap request ke endpoint ini secara otomatis menaikkan counter `view_count` sebesar 1.

### d. Halaman Statis Profil (Tentang Kami, Visi Misi, dll)
- **Endpoint**: `GET /api/v1/pages/{slug}`
- **Contoh**: `GET /api/v1/pages/tentang-kami`
- **Response Contoh (200)**:
```json
{
  "data": {
    "id": 1,
    "title": "Tentang Kami",
    "slug": "tentang-kami",
    "content": "<h2>Profil Instansi</h2><p>Penjelasan lengkap...</p>",
    "image": null,
    "sort_order": 1,
    "updated_at": "2026-11-04T02:24:23.000000Z"
  }
}
```

### e. Kategori Artikel
- **Endpoint**: `GET /api/v1/categories`
- **Response**: Daftar semua kategori aktif dengan jumlah post (`posts_count`).

### f. Slide Banner Beranda
- **Endpoint**: `GET /api/v1/slides`
- **Keterangan**: Hanya mengembalikan slide yang statusnya aktif dan berada dalam rentang tanggal tayang hari ini.
- **Response Contoh (200)**:
```json
{
  "data": [
    {
      "id": 1,
      "title": "Selamat Datang di Portal Layanan Publik",
      "description": "Deskripsi singkat banner promosi",
      "image": "http://localhost:8000/storage/slides/default-banner.jpg",
      "link_url": "/tentang-kami",
      "sort_order": 1
    }
  ]
}
```

### g. Menu Navigasi
- **Endpoint**: `GET /api/v1/menus/{location}`
- **Pilihan Location**: `header`, `footer`, `sidebar`
- **Response**: Struktur menu beserta `items` yang tersusun urut berdasarkan `sort_order`.

### h. Pengaturan Situs (Nama, Kontak, Logo)
- **Endpoint**: `GET /api/v1/settings`
- **Response Contoh (200)**:
```json
{
  "data": {
    "site_name": "Dinas Layanan Terpadu",
    "site_tagline": "Melayani dengan Cepat, Tepat, dan Transparan",
    "site_description": "Portal resmi profil instansi...",
    "contact_email": "kontak@instansi.go.id",
    "contact_phone": "(021) 1234-5678",
    "contact_whatsapp": "081234567890",
    "contact_address": "Jl. Merdeka No. 10, Jakarta Pusat"
  }
}
```

---

## 2. Modul Layanan Masyarakat & FAQ

### a. Topik Pertanyaan & FAQ
- **Endpoint**: `GET /api/v1/question-topics`
- **Response**: Daftar topik aktif beserta counter `questions_count` dan `faqs_count`.

### b. FAQ (Pertanyaan yang Sering Diajukan)
- **Endpoint**: `GET /api/v1/faqs`
- **Query Params**:
  - `topic` (string slug atau int id) — contoh: `?topic=pelayanan-umum`
  - `search` (string pencarian kata kunci)
- **Response Contoh (200)**:
```json
{
  "data": [
    {
      "id": 1,
      "question": "Berapa lama proses tanggapan terhadap pertanyaan masyarakat?",
      "answer": "<p>Tim admin akan meninjau dan menjawab maksimal 1x24 jam kerja.</p>",
      "sort_order": 1,
      "topic": {
        "id": 1,
        "name": "Pelayanan Umum",
        "slug": "pelayanan-umum"
      }
    }
  ]
}
```

### c. Arsip Pertanyaan Warga yang Sudah Dijawab (Publik)
- **Endpoint**: `GET /api/v1/questions`
- **Query Params**:
  - `page` (int)
  - `per_page` (int)
  - `topic` (string/id)
  - `search` (keyword)
- **Keterangan**: Nama penanya disamarkan sebagian secara otomatis untuk privasi (contoh: `B*** S******`).
- **Response Contoh (200)**:
```json
{
  "data": [
    {
      "id": 1,
      "name": "B*** S******",
      "subject": "Jam operasional layanan tatap muka",
      "body": "Selamat pagi, mohon informasi jam buka loket pelayanan...",
      "status": "answered",
      "created_at": "2026-11-04T02:24:37.000000Z",
      "topic": {
        "id": 1,
        "name": "Pelayanan Umum",
        "slug": "pelayanan-umum"
      },
      "latest_answer": {
        "id": 1,
        "body": "<p>Loket pelayanan kami buka setiap hari Senin - Jumat pukul 08.00 - 15.00 WIB.</p>",
        "status": "published",
        "answered_by": "Administrator",
        "answered_at": "2026-11-04T02:24:39.000000Z"
      }
    }
  ]
}
```

### d. Kirim Pertanyaan Baru (Form Masyarakat)
- **Endpoint**: `POST /api/v1/questions`
- **Rate Limit**: Maksimal 10 request per menit per IP.
- **Request Body**:
```json
{
  "topic_id": 1,
  "name": "Rina Marlina",
  "email": "rina@example.com",
  "phone": "081234567890",
  "subject": "Syarat legalisir dokumen",
  "body": "Selamat siang, apa saja dokumen yang perlu dibawa untuk proses legalisir berkas?"
}
```
- **Validation Rules**:
  - `topic_id`: Wajib, harus ada di tabel `question_topics`.
  - `name`: Wajib, string, max: 255.
  - `email`: Wajib, email valid.
  - `phone`: Opsional, nomor telepon.
  - `subject`: Wajib, string, max: 255.
  - `body`: Wajib, string, minimal 10 karakter, maksimal 5000 karakter.
- **Response Success (201 Created)**:
```json
{
  "message": "Pertanyaan Anda berhasil dikirim. Tim kami akan meninjau dan merespon secepatnya.",
  "data": {
    "id": 2,
    "subject": "Syarat legalisir dokumen",
    "status": "Menunggu Jawaban",
    "created_at": "2026-11-04T02:50:00.000000Z"
  }
}
```
- **Response Error Validasi (422)**:
```json
{
  "message": "Nama wajib diisi.",
  "errors": {
    "name": ["Nama wajib diisi."]
  }
}
```

