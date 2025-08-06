# Deployment ke Hosting - Laravel Scheduler Setup

## ⚠️ PENTING: Setup Cron Job di Hosting

Setelah upload ke hosting, scheduler TIDAK akan jalan otomatis. Anda perlu setup cron job di hosting.

## 🔧 Setup Cron Job di cPanel

### 1. Login ke cPanel hosting
### 2. Cari menu "Cron Jobs" 
### 3. Tambahkan cron job baru:

**Schedule (pilih salah satu):**

**Option A - Common Settings:**
- Minute: `*` 
- Hour: `*`
- Day: `*` 
- Month: `*`
- Weekday: `*`

**Option B - Once Per Minute:**
```
* * * * *
```

**Command:**
```bash
/usr/local/bin/php /home/[USERNAME]/public_html/artisan schedule:run >> /dev/null 2>&1
```

**Ganti `[USERNAME]` dengan username hosting Anda!**

### Contoh Path Umum:
- **Shared Hosting**: `/home/username/public_html/artisan`
- **VPS/Cloud**: `/var/www/html/artisan` atau `/var/www/resq-admin/artisan`

## 🔍 Cara Cek Path PHP di Hosting

Jika tidak yakin path PHP, buat file `phpinfo.php` di public folder:
```php
<?php phpinfo(); ?>
```
Buka di browser, cari "php" path.

Atau coba command ini di terminal hosting:
```bash
which php
whereis php
php -v
```

## 📝 Template Cron Job untuk Hosting Populer

### **Hostinger:**
```bash
/usr/bin/php81 /home/u123456789/domains/yourdomain.com/public_html/artisan schedule:run
```

### **cPanel/WHM:**
```bash
/usr/local/bin/php /home/username/public_html/artisan schedule:run
```

### **Shared Hosting Umum:**
```bash
php /home/username/public_html/artisan schedule:run
```

## ✅ Verifikasi Setup

### 1. **Test Command Manual** (via SSH/Terminal hosting):
```bash
cd /path/to/your/project
php artisan schedule:list
php artisan schedule:run
php artisan app:destroy-expired-order
```

### 2. **Check Logs**:
```bash
tail -f storage/logs/expired-orders.log
tail -f storage/logs/laravel.log
```

### 3. **Monitor di Laravel Log**:
Setiap 5 menit akan ada log:
- Success: "Expired orders command completed successfully"
- Failure: "Expired orders command failed"

## 🚨 Troubleshooting

### Command tidak jalan?

1. **Check PHP path**: Pastikan path PHP benar
2. **Check project path**: Pastikan path ke artisan file benar  
3. **Check permissions**: File artisan harus executable
4. **Check logs**: Lihat `storage/logs/laravel.log` untuk error
5. **Check timezone**: Pastikan server timezone sesuai dengan aplikasi

### Set timezone di hosting:
```bash
php artisan config:cache
```

## 📊 Alternative: Manual Cron untuk Testing

Jika mau test manual dulu, bisa setup cron langsung ke command:
```bash
*/5 * * * * /usr/local/bin/php /home/username/public_html/artisan app:destroy-expired-order
```

## 🔄 Restart Required

Setelah setup cron job di hosting:
1. **Clear cache**: `php artisan config:cache`
2. **Clear cache**: `php artisan route:cache` 
3. **Wait 5-10 menit** untuk test pertama kali

---

## ⚡ Quick Setup Checklist untuk Hosting:

- [ ] Upload semua file project ke hosting
- [ ] Setup database dan .env
- [ ] Run `php artisan migrate` (jika perlu)
- [ ] Setup cron job: `* * * * * php /path/to/artisan schedule:run`
- [ ] Test: `php artisan schedule:list`
- [ ] Monitor: `tail -f storage/logs/expired-orders.log`
- [ ] Wait 5-10 menit untuk eksekusi pertama
