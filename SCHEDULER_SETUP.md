# Laravel Scheduler Setup untuk Windows

## Setup Otomatis

Command `app:destroy-expired-order` telah dikonfigurasi untuk berjalan setiap 5 menit dengan pengaturan berikut:

- **Interval**: Setiap 5 menit (`everyFiveMinutes()`)
- **Prevent Overlap**: Mencegah eksekusi bersamaan (`withoutOverlapping()`)
- **Background**: Berjalan di background (`runInBackground()`)
- **Logging**: Output disimpan ke `storage/logs/expired-orders.log`

## Cara Setup Windows Task Scheduler

### Method 1: Menggunakan Task Scheduler GUI

1. Buka **Task Scheduler** (ketik "Task Scheduler" di Windows Search)
2. Klik **Create Basic Task** di panel kanan
3. Isi pengaturan:
   - **Name**: Laravel Scheduler - RESQ Admin
   - **Description**: Menjalankan Laravel scheduler setiap menit untuk automasi system
   - **Trigger**: Daily, mulai hari ini
   - **Start time**: 00:00:00
   - **Recur every**: 1 days
4. **Action**: Start a program
   - **Program/script**: `c:\laragon\www\resq-admin\setup-scheduler.bat`
5. **Advanced Settings**:
   - ✅ Run whether user is logged on or not
   - ✅ Run with highest privileges
   - **Repeat task every**: 1 minute
   - **for a duration of**: Indefinitely

### Method 2: Menggunakan Command Line

Buka Command Prompt sebagai Administrator dan jalankan:

```cmd
schtasks /create /tn "Laravel Scheduler RESQ" /tr "c:\laragon\www\resq-admin\setup-scheduler.bat" /sc minute /mo 1 /ru SYSTEM
```

## Testing

### Test Manual Command
```bash
cd c:\laragon\www\resq-admin
php artisan app:destroy-expired-order
```

### Test Scheduler
```bash
cd c:\laragon\www\resq-admin
php artisan schedule:run
```

### Lihat Log
```bash
# Log scheduler umum
tail -f storage/logs/scheduler.log

# Log expired orders khusus  
tail -f storage/logs/expired-orders.log

# Log Laravel umum
tail -f storage/logs/laravel.log
```

## Monitoring

- **Command berjalan setiap**: 5 menit
- **Scheduler check setiap**: 1 menit (by cron)
- **Log lokasi**: 
  - Scheduler: `storage/logs/scheduler.log`
  - Expired Orders: `storage/logs/expired-orders.log`
  - Laravel: `storage/logs/laravel.log`

## Troubleshooting

Jika command tidak berjalan:

1. **Check permissions**: Pastikan Windows Task Scheduler running sebagai SYSTEM
2. **Check paths**: Pastikan path ke PHP dan project benar
3. **Check logs**: Lihat `storage/logs/scheduler.log` untuk error
4. **Manual test**: Jalankan `php artisan schedule:list` untuk melihat scheduled commands
5. **Manual run**: `php artisan app:destroy-expired-order` untuk test langsung
