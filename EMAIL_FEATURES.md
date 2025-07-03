# Fitur Email E-Commerce Hexadigital

## Overview

Sistem email otomatis telah diimplementasikan untuk mengirim notifikasi dan invoice kepada pelanggan saat melakukan transaksi di E-Commerce Hexadigital.

## Fitur yang Tersedia

### 1. Email Notifikasi Pesanan Baru

-   **Trigger**: Saat pelanggan melakukan checkout
-   **Tujuan**: Memberitahu pelanggan bahwa pesanan telah berhasil dibuat
-   **Konten**:
    -   Ringkasan pesanan
    -   Detail produk yang dipesan
    -   Total pembayaran
    -   Status pesanan
    -   Langkah selanjutnya

### 2. Email Invoice

-   **Trigger**: Saat pelanggan melakukan checkout
-   **Tujuan**: Mengirim invoice resmi dalam format HTML
-   **Konten**:
    -   Header invoice dengan logo perusahaan
    -   Informasi pelanggan
    -   Detail produk dengan harga
    -   Total pembayaran
    -   Status pembayaran
    -   Footer dengan informasi kontak

### 3. Email Konfirmasi Pembayaran

-   **Trigger**: Saat pelanggan mengupload bukti pembayaran
-   **Tujuan**: Memberitahu pelanggan bahwa pembayaran telah diterima
-   **Konten**:
    -   Konfirmasi penerimaan pembayaran
    -   Detail pesanan
    -   Langkah selanjutnya dalam proses

## File yang Dibuat/Dimodifikasi

### Controllers

-   `app/Controllers/Checkout.php` - Menambahkan pengiriman email saat checkout
-   `app/Controllers/Orders.php` - Menambahkan pengiriman email konfirmasi pembayaran
-   `app/Controllers/TestEmail.php` - Controller untuk testing fitur email

### Services

-   `app/Services/EmailService.php` - Service class untuk menangani pengiriman email

### Views

-   `app/Views/emails/invoice.php` - Template email invoice
-   `app/Views/emails/order_notification.php` - Template email notifikasi pesanan
-   `app/Views/test/email_test.php` - Halaman untuk testing email
-   `app/Views/pages/orders.php` - Menambahkan tombol kirim invoice via email

### Configuration

-   `app/Config/Autoload.php` - Menambahkan helper yang diperlukan

## Cara Penggunaan

### 1. Email Otomatis

Email akan dikirim secara otomatis saat:

-   Pelanggan melakukan checkout (notifikasi + invoice)
-   Pelanggan mengupload bukti pembayaran (konfirmasi pembayaran)

### 2. Kirim Invoice Manual

Pelanggan dapat mengirim ulang invoice via email dengan:

1. Buka halaman "Pesanan Saya"
2. Klik tombol "Kirim Invoice via Email"
3. Invoice akan dikirim ke email yang terdaftar

### 3. Testing Email

Untuk testing fitur email:

1. Akses `/test-email/list-orders`
2. Pilih pesanan yang ingin ditest
3. Klik tombol test yang diinginkan

## Konfigurasi Email

Email dikonfigurasi di `app/Config/Email.php`:

-   **SMTP Host**: smtp.gmail.com
-   **Port**: 465
-   **Encryption**: SSL
-   **From Email**: edystikom123@gmail.com
-   **From Name**: E-Commerce Hexadigital

## Template Email

### Struktur Template

Semua template email menggunakan:

-   HTML dengan CSS inline untuk kompatibilitas
-   Responsive design
-   Branding E-Commerce Hexadigital
-   Informasi lengkap pesanan

### Customization

Template dapat dikustomisasi dengan mengedit file di:

-   `app/Views/emails/invoice.php`
-   `app/Views/emails/order_notification.php`

## Error Handling

Sistem email memiliki error handling yang baik:

-   Log error ke file log
-   Fallback jika email gagal dikirim
-   Pesan feedback ke user
-   Tidak mengganggu proses utama aplikasi

## Logging

Semua aktivitas email dicatat di log:

-   Email berhasil dikirim
-   Email gagal dikirim
-   Error detail untuk debugging

## Keamanan

-   Validasi kepemilikan pesanan sebelum mengirim email
-   Sanitasi data sebelum ditampilkan di email
-   Penggunaan HTTPS untuk link di email
-   Validasi input user

## Troubleshooting

### Email Tidak Terkirim

1. Cek konfigurasi SMTP di `app/Config/Email.php`
2. Pastikan kredensial email benar
3. Cek log error di `writable/logs/`
4. Test dengan controller `TestEmail`

### Template Tidak Tampil

1. Pastikan file template ada di `app/Views/emails/`
2. Cek helper yang diperlukan di `app/Config/Autoload.php`
3. Pastikan fungsi `esc()` tersedia

### Link di Email Tidak Berfungsi

1. Pastikan `baseURL` dikonfigurasi dengan benar
2. Gunakan `site_url()` helper untuk generate URL
3. Test link secara manual

## Future Enhancements

1. **Email Queue**: Implementasi queue untuk email massal
2. **Email Template Builder**: Interface untuk edit template
3. **Email Tracking**: Tracking pembacaan email
4. **Multi-language**: Support bahasa Indonesia dan Inggris
5. **Email Preferences**: User dapat set preferensi email
6. **SMS Integration**: Kombinasi email dan SMS notification
