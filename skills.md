# Genji Web Project Skills

Panduan kerja untuk pengembangan project Genji Web berbasis CodeIgniter 3 HMVC.

## 1. Arsitektur Project

- `application/config/`: konfigurasi aplikasi, route, database, autoload, dan hooks.
- `application/controllers/`: controller aplikasi umum.
- `application/models/`: model bersama untuk akses dan aturan data.
- `application/modules/<module>/controllers/`: controller fitur/module.
- `application/modules/<module>/models/`: model khusus module bila memang diperlukan.
- `application/modules/<module>/views/`: view fitur dan file JavaScript view.
- `application/views/`: view umum dan partial layout.
- `application/core/`: ekstensi CodeIgniter, misalnya `MY_Loader` dan `MY_Router`.
- `application/libraries/`: library aplikasi dan integrasi pihak ketiga.
- `database/`: file SQL migration atau perubahan schema.
- `themes/`: asset dan tema tampilan.
- `uploads/`: file upload pengguna.
- `vendor/`: dependency Composer. Jangan mengedit manual.

## 2. Tree Module

Module baru mengikuti pola berikut:

```text
application/modules/<module>/
├── controllers/
│   └── <Module>.php
├── models/                 # opsional
├── views/
│   ├── index.php
│   ├── form.php            # opsional
│   └── main.js.php         # opsional
└── config/                 # opsional
```

Untuk model yang dipakai lintas module, gunakan:

```text
application/models/<Feature>Model.php
```

Jangan membuat folder baru di root jika file tersebut memiliki tempat yang jelas di `application/`, `database/`, atau `themes/`.

## 3. Penamaan File dan Class

- Nama controller memakai PascalCase, contoh `User_package.php` mengikuti pola module yang sudah ada.
- Nama class controller harus sama dengan nama file.
- Nama model memakai PascalCase dan akhiran `Model`, contoh `UserPackageModel.php`.
- Nama module dan URL memakai snake_case, contoh `user_package` dan `my_payment`.
- Nama view mengikuti kegunaan halaman: `index.php`, `form.php`, `main.js.php`.
- Nama fungsi memakai snake_case, contoh `ajax_get_all`, `ajax_save`, `upload_manual_proof`.
- Gunakan nama variabel deskriptif. Hindari nama satu huruf kecuali counter yang sangat lokal.

## 4. Pola Controller

Controller module umumnya:

```php
<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Example extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['AppModel', 'ExampleModel']);
    }
}
```

Aturan:

- Panggil `parent::__construct()`.
- Gunakan `AppBackend` untuk halaman backend yang membutuhkan session dan layout aplikasi.
- Endpoint AJAX memanggil `$this->handle_ajax_request()` terlebih dahulu.
- Response AJAX konsisten berupa JSON dengan bentuk `status` dan `data`, atau response DataTables dari `AppModel->getData_dtAjax()`.
- Validasi input dengan `form_validation`; jangan mempercayai `POST`, URL parameter, atau file upload.
- Batasi data berdasarkan user/session di server, bukan hanya di JavaScript.
- Gunakan `base_url()` untuk URL aplikasi.
- Jangan menaruh query kompleks di view.

## 5. Pola Model dan Database

- Model memakai `$this->db` dan nama tabel private, contoh `private $_table = 'user_packages';`.
- Gunakan method `getAll`, `getDetail`, `insert`, `update`, dan `delete` bila sesuai pola model yang sudah ada.
- Method model mengembalikan object/result sesuai pola project, bukan format baru tanpa alasan.
- Untuk DataTables server-side, gunakan `AppModel->getData_dtAjax()` dan konfigurasi `select_column`, `table_name`, `table_join`, `static_conditional_spec`, serta sorting.
- Gunakan `static_conditional_spec` untuk equality. Jangan memakai `static_conditional` untuk ID karena helper tersebut menghasilkan `LIKE`.
- Escape atau gunakan Query Builder untuk input dinamis. Jangan merangkai SQL mentah dari input pengguna.
- Saat JOIN dapat menghasilkan banyak baris, pastikan hasil tetap satu baris per record utama dengan subquery latest record, agregasi, atau kondisi JOIN yang tepat.
- Untuk perubahan schema, tambahkan file baru di `database/` dengan nama deskriptif, misalnya `add_manual_payment_verification.sql`.
- Jangan mengubah file migration lama yang sudah pernah diterapkan tanpa alasan kompatibilitas yang jelas.

## 6. Pola View dan JavaScript

- View utama module berada di `views/index.php`.
- Form reusable berada di `views/form.php`.
- JavaScript module berada di `views/main.js.php` agar URL, CSRF, dan konfigurasi PHP dapat digunakan.
- Gunakan Bootstrap yang sudah dipakai project: `data-toggle="modal"` dan `data-toggle="dropdown"`, bukan atribut Bootstrap 5.
- Untuk DataTables, jumlah header tabel harus sama dengan jumlah item `columns` di JavaScript.
- Gunakan event delegation untuk tombol yang dibuat oleh DataTables, contoh `$(document).on('click', '.class-name', ...)`.
- Tampilkan status pengguna dengan badge yang sesuai nilai database.
- Escape data user ketika memasukkannya ke atribut HTML atau markup dinamis.
- Jangan meninggalkan container kosong, placeholder tinggi, atau markup payment gateway yang tidak digunakan.
- Modal harus memiliki ID yang sama antara markup dan JavaScript.

## 7. Upload dan Pembayaran

- Validasi tipe dan ukuran file melalui library upload CodeIgniter.
- Simpan file upload dengan nama terenkripsi, bukan nama asli pengguna.
- Hapus file lama setelah file baru berhasil disimpan.
- Manual payment memakai `payment_type = 'manual'` dan status verifikasi `pending`, `approved`, atau `rejected`.
- Midtrans memakai status webhook sebagai sumber kebenaran transaksi.
- Aktivasi paket hanya dilakukan setelah pembayaran benar-benar settlement/capture atau bukti manual disetujui admin.
- Jangan menganggap tombol sukses di browser sebagai bukti pembayaran final.
- Semua endpoint pembayaran harus memeriksa kepemilikan transaksi berdasarkan user/session.

## 8. Cara Menambah Fitur

1. Tentukan module dan controller pemilik behavior.
2. Baca controller, model, view, dan endpoint tetangga sebelum mengedit.
3. Tambahkan migration SQL jika schema berubah.
4. Implementasikan validasi di controller/model dan authorization di server.
5. Tambahkan atau ubah view dan `main.js.php` dengan pola yang sudah ada.
6. Pastikan response AJAX, jumlah kolom DataTables, modal ID, dan URL tetap cocok.
7. Jalankan lint PHP pada file yang berubah.
8. Uji alur utama: valid input, invalid input, empty state, dan status error.

## 9. Validasi Lokal

Perintah dasar:

```powershell
php -l application/modules/<module>/controllers/<Controller>.php
php -l application/modules/<module>/views/index.php
php -l application/modules/<module>/views/main.js.php
```

Untuk perubahan database, periksa query dan migration sebelum menjalankan SQL. Jangan menjalankan migration berulang jika tidak idempotent.

## 10. Larangan

- Jangan mengedit `system/` atau `vendor/` untuk kebutuhan fitur aplikasi.
- Jangan melakukan `git reset --hard`, `git checkout --`, atau menghapus perubahan pengguna.
- Jangan commit otomatis kecuali diminta.
- Jangan melakukan refactor luas ketika bug dapat diperbaiki dengan perubahan lokal.
- Jangan menambahkan dependency baru jika library yang tersedia sudah mencukupi.
