# Sistem Absensi Kerja Praktek (KP)

Aplikasi web komprehensif untuk mengelola program Kerja Praktek (KP), dilengkapi dengan panel admin yang tangguh dan portal peserta yang mudah digunakan.

## 🚀 Fitur Utama

### 🛠️ Sisi Admin (Filament Panel)

#### 📊 Dashboard & Monitoring

- **Dashboard Interaktif**: Statistik real-time, grafik, dan ringkasan program kerja praktek
- **Widget Statistik**: Monitoring jumlah peserta aktif, pending, dan completed

#### 📁 Manajemen Master Data

- **🏢 Bagian**: Kelola unit/bagian dengan sistem kuota dan monitoring slot tersedia
- **🏫 Instansi**: Kelola daftar universitas dan instansi mitra
- **👥 Admin**: Kelola akun administrator dengan auto-verification

#### 📑 Manajemen Peserta

- **🟢 Pengguna Aktif**: Tracking dan monitoring peserta yang sedang aktif
- **🟡 Pengguna Pending**: Sistem approval untuk pendaftaran peserta baru
- **🎓 Manajemen Kelulusan**: Proses penyelesaian program peserta
- **📥 Laporan Akhir**: Manajemen terpusat untuk laporan yang disubmit peserta

#### 📈 Reporting & Export

- **Laporan Detail**: Generate laporan absensi dengan filter lanjutan (tanggal, bagian, status)
- **Export Data**: Export data peserta ke Excel/CSV dengan filter custom

### 👤 Sisi Pengguna (Portal Peserta)

#### 🔐 Autentikasi

- **Pendaftaran Multi-Step**: Proses registrasi bertahap yang user-friendly
- **Login Aman**: Autentikasi dengan email verification
- **Reset Password**: Pemulihan kata sandi via email

#### 🏠 Dashboard Pribadi

- **Statistik Personal**: Visualisasi tren absensi dan status KP
- **Quick Actions**: Akses cepat ke fitur check-in dan profil

#### ⏰ Sistem Absensi

- **📍 Check-in Harian**:
    - Verifikasi dengan foto selfie
    - Tracking lokasi GPS
    - Validasi waktu check-in
- **📜 Riwayat Absensi**: Log kehadiran lengkap dengan detail
- **📥 Export Personal**: Download data absensi untuk keperluan pribadi

#### 📄 Profil & Submission

- **⚙️ Manajemen Profil**: Update informasi pribadi dan akademik
- **📤 Upload Laporan**: Submit laporan akhir KP langsung dari portal

---

## 💻 Tech Stack

| Kategori              | Teknologi                                                                          |
| --------------------- | ---------------------------------------------------------------------------------- |
| **Backend Framework** | [Laravel 12](https://laravel.com)                                                  |
| **Admin Panel**       | [Filament v4](https://filamentphp.com)                                             |
| **Frontend**          | [Livewire](https://livewire.laravel.com) + [Tailwind CSS](https://tailwindcss.com) |
| **Database**          | MySQL / MariaDB                                                                    |
| **Authentication**    | Laravel Breeze + Custom Email Verification                                         |

---

## 🛠️ Instalasi & Konfigurasi

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB

### Langkah Instalasi

1. **Clone repository**

    ```bash
    git clone https://github.com/liantarill/project-kp.git
    cd project-kp
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Setup environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi database**

    Edit file `.env` dan sesuaikan konfigurasi database:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=project_kp
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Jalankan migrasi & seeder**

    ```bash
    php artisan migrate --seed
    ```

6. **Build assets**

    ```bash
    npm run build
    # atau untuk development
    npm run dev
    ```

7. **Jalankan aplikasi**

    ```bash
    php artisan serve
    ```

    Akses aplikasi di: `http://localhost:8000`

---

## 🔑 Default Credentials

Setelah menjalankan seeder, gunakan kredensial berikut untuk login:

**Admin Panel** (`/admin`)

- Email: `admin@example.com`
- Password: `password`

**User Portal** (`/login`)

- Email: `user@example.com`
- Password: `password`

> ⚠️ **Penting**: Segera ubah password default setelah login pertama kali!

---

## 📧 Kontak

Untuk pertanyaan atau dukungan, silakan hubungi:

- **Email**: herlianyusuf1@gmail.com
- **GitHub Issues**: [Create an issue](https://github.com/liantarill/project-kp/issues)

---

<div align="center">
  <p>Dibuat dengan menggunakan Laravel & Filament</p>
</div>
