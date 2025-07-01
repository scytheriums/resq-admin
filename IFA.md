# Initial Functional Assessment (IFA) - Panel Admin ResQin

Dokumen ini menguraikan persyaratan fungsional untuk **Panel Admin ResQin**. Tujuannya adalah untuk memberikan panduan dalam mengembangkan API dan antarmuka yang diperlukan oleh admin untuk mengelola operasi, data master, dan konfigurasi sistem.

Dokumen ini juga mencakup definisi status pesanan, pembayaran, dan struktur database yang menjadi acuan utama dalam alur kerja sistem.

---

## Daftar Isi

- [Pencatatan Log Aktivitas (Logging)](#pencatatan-log-aktivitas-logging)
- [Definisi Status](#definisi-status)
  - [Status Pesanan](#status-pesanan-order_status)
  - [Status Pembayaran](#status-pembayaran-payment_status)
- [1. Alur Kerja Admin Utama](#1-alur-kerja-admin-utama)
- [2. Dashboard Monitoring](#2-dashboard-monitoring)
- [3. Manajemen Pesanan](#3-manajemen-pesanan)
- [4. Manajemen Driver](#4-manajemen-driver)
- [5. Manajemen Data Master (CRUD)](#5-manajemen-data-master-crud)
- [6. Manajemen Konfigurasi Aplikasi](#6-manajemen-konfigurasi-aplikasi)
- [7. Manajemen Log Aktivitas](#7-manajemen-log-aktivitas)
- [8. Manajemen Chat](#8-manajemen-chat)
- [9. Definisi Skema & Model Data](#9-definisi-skema--model-data)

---

## Pencatatan Log Aktivitas (Logging)
**Penting untuk Tim Backend:** Setiap aksi penting yang dijelaskan dalam dokumen ini **wajib** dicatat dalam `activity_logs`. Pencatatan log harus diimplementasikan secara **asynchronous** (misalnya, menggunakan *queue* atau *event emitter*) untuk memastikan tidak menambah *response time* ke admin.

---

## Definisi Status

### Status Pesanan (`order_status`)

| Value                  | Caption di UI              | Kondisi                                                 |
| ---------------------- | -------------------------- | ------------------------------------------------------- |
| `created`              | Pesanan Dibuat             | Baru dipesan oleh user.                                 |
| `booked`               | Booked                     | Setelah user membayar booking fee, menunggu konfirmasi admin. |
| `confirmed`            | Dikonfirmasi Admin         | Telah dikonfirmasi oleh admin.                          |
| `assigned_to_driver`   | Driver Ditugaskan          | Admin telah menugaskan driver untuk berangkat.          |
| `in_progress_pickup`   | Driver Menuju Titik Jemput | Driver berangkat dari base ke titik jemput.             |
| `in_progress_deliver`  | Driver Menuju Titik Tujuan | Driver berangkat dari titik jemput ke titik tujuan.     |
| `completed`            | Selesai                    | Pesanan sudah selesai (ditandai oleh admin).            |
| `cancelled_by_user`    | Dibatalkan                 | Pesanan dibatalkan oleh user.                           |
| `cancelled_by_system`  | Dibatalkan                 | Pesanan dibatalkan oleh admin.                          |

### Status Pembayaran (`payment_status`)

| Value                   | Caption di UI          | Kondisi                                                 |
| ----------------------- | ---------------------- | ------------------------------------------------------- |
| `booking_fee_pending`   | Menunggu Booking Fee   | Pesanan dibuat dan menunggu pembayaran booking fee.     |
| `booking_fee_paid`      | Booking Fee Lunas      | Booking fee sudah dibayar oleh user.                    |
| `final_payment_pending` | Tagihan Belum Dibayar  | Setelah admin konfirmasi dan mengirim notifikasi tagihan akhir. |
| `final_payment_paid`    | Tagihan Lunas          | User telah melunasi sisa tagihan akhir.                 |

---

## 1. Alur Kerja Admin Utama

1.  **Notifikasi Booking Fee Lunas:** Admin menerima notifikasi via **Telegram** ketika ada pesanan yang `booking_fee`-nya sudah lunas (status pesanan: `booked`).
2.  **Konfirmasi & Penugasan:** Admin membuka detail pesanan, menambahkan layanan medis jika perlu, menghitung tagihan akhir, lalu menugaskan driver yang tersedia.
3.  **Notifikasi Pembayaran Akhir Lunas:** Admin menerima notifikasi via **Telegram** ketika pengguna telah melunasi sisa tagihan.
4.  **Instruksi ke Driver:** Admin menginformasikan driver untuk berangkat ke lokasi penjemputan.
5.  **Menyelesaikan Pesanan:** Setelah layanan selesai, admin menandai pesanan sebagai `completed` melalui panel admin.

---

## 2. Dashboard Monitoring

### `GET /api/admin/dashboard`
Mengambil data ringkasan untuk ditampilkan di dashboard utama admin dari sudut pandang "helicopter view".
- **Tabel Terkait:** `orders`, `drivers`, `payments` (agregat)
- **Query Params (opsional):**
  - `period`: 'daily' | 'weekly' | 'monthly' (default: 'daily')
- **Komponen UI yang Ditampilkan:**
  - **KPI Cards:** Kartu-kartu yang menampilkan metrik kunci.
    - Total Pesanan Hari Ini
    - Pesanan Sedang Berjalan (ongoing)
    - Driver Tersedia
    - Pendapatan Hari Ini
  - **Grafik Tren Pesanan:** Grafik batang atau garis yang menampilkan jumlah pesanan selama 7 hari terakhir.
  - **Daftar Pesanan Terbaru:** Tabel ringkas yang menampilkan 5 pesanan terakhir yang masuk (status `created` atau `booked`).
  - **Status Driver:** Ringkasan jumlah driver berdasarkan status (Tersedia, Sedang Bertugas, Tidak Aktif).
- **Output (Success 200 OK):**
  ```json
  {
    "summary": {
      "todayOrders": 15,
      "ongoingOrders": 4,
      "availableDrivers": 8,
      "todayRevenue": 7500000
    },
    "orderTrend": [
      { "date": "2024-07-18", "count": 12 },
      { "date": "2024-07-19", "count": 18 },
      { "date": "2024-07-20", "count": 14 },
      { "date": "2024-07-21", "count": 20 },
      { "date": "2024-07-22", "count": 16 },
      { "date": "2024-07-23", "count": 22 },
      { "date": "2024-07-24", "count": 15 }
    ],
    "recentOrders": [
      {
        "orderId": 130,
        "orderNumber": "RESQ-654321",
        "userName": "Citra Lestari",
        "status": "booked",
        "timestamp": "2024-07-24T14:30:00Z"
      },
      {
        "orderId": 129,
        "orderNumber": "RESQ-654320",
        "userName": "Doni Firmansyah",
        "status": "created",
        "timestamp": "2024-07-24T14:25:00Z"
      }
    ],
    "driverStatus": {
      "available": 8,
      "onDuty": 4,
      "unavailable": 2
    }
  }
  ```

---

## 3. Manajemen Pesanan

### `GET /api/admin/orders`
Melihat daftar pesanan berdasarkan status.
- **Tabel Terkait:** `orders`
- **Query Params (opsional):**
  - `status`: 'booked' | 'ongoing' | 'completed' | 'cancelled'
- **Output (Success 200 OK):**
  ```json
  [
    {
      "orderId": 123,
      "orderNumber": "RESQ-123456",
      "userName": "Budi Santoso",
      "orderDate": "2024-07-20T10:00:00Z",
      "status": "booked",
      "totalBill": 550000
    }
  ]
  ```

### `GET /api/admin/orders/{orderId}`
Melihat detail lengkap satu pesanan.
- **Tabel Terkait:** `orders`, `users`, `drivers`, `ambulance_types`, `purposes`, `order_additional_services`, `additional_services`, `reviews`
- **Output (Success 200 OK):**
  ```json
  {
    "orderId": 123,
    "orderNumber": "RESQ-123456",
    "status": "booked",
    "paymentStatus": "booking_fee_paid",
    "user": {
      "name": "Budi Santoso",
      "whatsapp": "081234567890"
    },
    "pickup": {
      "address": "Jl. Kenangan No. 10, Bandung",
      "latitude": -6.9023,
      "longitude": 107.6186,
      "province": "Jawa Barat",
      "city": "Kota Bandung",
      "district": "Kecamatan Regol",
      "subdistrict": "Kelurahan Ciseureuh",
      "postalCode": "40255"
    },
    "destination": {
      "address": "Rumah Sakit Al-Islam",
      "latitude": -6.9389,
      "longitude": 107.6691,
      "province": "Jawa Barat",
      "city": "Kota Bandung",
      "district": "Kecamatan Rancasari",
      "subdistrict": "Kelurahan Manjahlega",
      "postalCode": "40286"
    },
    "ambulanceType": { "id": 4, "name": "APV" },
    "purpose": { "id": 1, "name": "Kegawat Daruratan (Gadar)" },
    "notes": "Pasien mengeluh sesak napas.",
    "bill": {
      "basePrice": 500000,
      "purposeFee": 0,
      "bookingFee": 50000,
      "additionalServicesFee": 0,
      "totalBill": 550000
    },
    "driver": {
      "id": 5,
      "name": "Agus Setiawan",
      "averageRating": 4.75
    },
    "review": {
      "rating": 5,
      "comment": "Sangat memuaskan!"
    }
  }
  ```

### `PUT /api/admin/orders/{orderId}/confirm`
Mengonfirmasi pesanan, menambahkan layanan medis, menghitung tagihan akhir, dan menugaskan driver.
- **Tabel Terkait:** `orders`, `drivers`, `order_additional_services`, `activity_logs`
- **Proses:**
  1.  Admin menambahkan layanan tambahan jika diperlukan. Backend menghitung total biayanya dari data master.
  2.  Admin menugaskan driver yang tersedia (`driver_id`). Sistem harus memfilter driver yang ditampilkan kepada admin agar hanya menampilkan driver yang `is_available` dan memiliki `ambulance_type_id` yang sesuai.
  3.  Backend mengubah status `is_available` driver yang ditugaskan menjadi `FALSE`.
  4.  Backend menghitung `total_bill` akhir (`base_price` + `purpose_fee` + `additional_services_fee`).
  5.  Backend mengubah status pesanan menjadi `confirmed`.
  6.  Backend mengirim notifikasi push ke pengguna bahwa pesanan telah dikonfirmasi dan ada tagihan akhir yang perlu dibayar.
- **Input:**
  ```json
  {
    "driverId": 5,
    "additionalServiceIds": [1, 2]
  }
  ```
- **Output (Success 200 OK):**
  ```json
  {
    "orderId": 123,
    "status": "confirmed",
    "totalBill": 750000,
    "message": "Order confirmed and driver assigned successfully."
  }
  ```
- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Data yang Disimpan:**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "ORDER_CONFIRMED",
      "description": "Admin ([Nama Admin]) mengonfirmasi pesanan [Nomor Pesanan] dan menugaskan driver [Nama Driver].",
      "details": {
        "adminId": "ID admin",
        "orderId": "ID pesanan",
        "driverId": "ID driver",
        "additionalServiceIds": [1, 2]
      }
    }
    ```

### `POST /api/admin/orders/{orderId}/complete`
Menandai pesanan sebagai selesai.
- **Tabel Terkait:** `orders`, `drivers`, `activity_logs`
- **Proses:**
    1. Mengubah status pesanan menjadi `completed`.
    2. Mengubah status `is_available` driver yang bertugas menjadi `TRUE`.
- **Output (Success 200 OK):**
  ```json
  {
    "message": "Order has been successfully marked as completed."
  }
  ```
- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Data yang Disimpan:**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "ORDER_COMPLETED",
      "description": "Admin ([Nama Admin]) menandai pesanan [Nomor Pesanan] sebagai selesai.",
      "details": { "adminId": "ID admin", "orderId": "ID pesanan", "driverId": "ID driver" }
    }
    ```

### `POST /api/admin/orders/{orderId}/cancel`
Membatalkan pesanan.
- **Tabel Terkait:** `orders`, `activity_logs`
- **Proses:** 
    1. Mengubah status pesanan menjadi `cancelled_by_system`.
- **Input:**
  ```json
  {
    "reason": "Driver tidak tersedia di area tersebut."
  }
  ```
- **Output (Success 200 OK):**
  ```json
  {
    "message": "Order has been successfully cancelled."
  }
  ```
- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Data yang Disimpan:**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "ORDER_CANCELLED_BY_SYSTEM",
      "description": "Admin ([Nama Admin]) membatalkan pesanan [Nomor Pesanan] dengan alasan: [Alasan Pembatalan].",
      "details": { "adminId": "ID admin", "orderId": "ID pesanan", "reason": "Alasan Pembatalan" }
    }
    ```

---

## 4. Manajemen Driver

### `GET /api/admin/drivers`
Melihat daftar semua driver.
- **Tabel Terkait:** `drivers`, `ambulance_types`
- **Query Params (opsional):**
  - `is_available`: true | false
- **Output (Success 200 OK):**
  ```json
  [
    {
      "id": 1,
      "name": "Agus Setiawan",
      "phoneNumber": "081122334455",
      "licensePlate": "D 1234 ABC",
      "isAvailable": true,
      "ambulanceType": "APV",
      "baseAddress": "Jl. Peta No. 24, Bandung",
      "averageRating": 4.75
    }
  ]
  ```

### `POST /api/admin/drivers`
Menambah driver baru.
- **Tabel Terkait:** `drivers`, `activity_logs`
- **Input:**
  ```json
  {
    "name": "Bambang Pamungkas",
    "phoneNumber": "089988776655",
    "telegramChatId": "123456789",
    "licensePlate": "D 5678 XYZ",
    "ambulanceTypeId": 4,
    "baseAddress": "Jl. Soekarno Hatta No. 100, Bandung",
    "baseLatitude": -6.94,
    "baseLongitude": 107.6
  }
  ```
- **Output (Success 201 Created):**
  ```json
  {
    "id": 2,
    "name": "Bambang Pamungkas",
    "phoneNumber": "089988776655",
    "telegramChatId": "123456789",
    "licensePlate": "D 5678 XYZ",
    "isAvailable": true,
    "ambulanceTypeId": 4,
    "baseAddress": "Jl. Soekarno Hatta No. 100, Bandung",
    "baseLatitude": -6.94,
    "baseLongitude": 107.6,
    "averageRating": 0.00
  }
  ```
- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Data yang Disimpan:**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "DRIVER_CREATED",
      "description": "Admin ([Nama Admin]) menambahkan driver baru: [Nama Driver Baru].",
      "details": { "adminId": "ID admin", "driverId": "ID driver baru" }
    }
    ```

### `PUT /api/admin/drivers/{driverId}`
Mengubah data driver.
- **Tabel Terkait:** `drivers`, `activity_logs`
- **Input:**
  ```json
  {
    "name": "Bambang Pamungkas",
    "isAvailable": false,
    "baseAddress": "Jl. Cibaduyut No. 20, Bandung"
  }
  ```
- **Output (Success 200 OK):**
  ```json
  {
    "id": 2,
    "name": "Bambang Pamungkas",
    "phoneNumber": "089988776655",
    "telegramChatId": "123456789",
    "licensePlate": "D 5678 XYZ",
    "isAvailable": false,
    "ambulanceTypeId": 4,
    "baseAddress": "Jl. Cibaduyut No. 20, Bandung",
    "baseLatitude": -6.94,
    "baseLongitude": 107.6,
    "averageRating": 4.50
  }
  ```
- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Data yang Disimpan:**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "DRIVER_UPDATED",
      "description": "Admin ([Nama Admin]) memperbarui data driver: [Nama Driver].",
      "details": { "adminId": "ID admin", "driverId": "ID driver yang diubah" }
    }
    ```

### `DELETE /api/admin/drivers/{driverId}`
Menghapus data driver.
- **Tabel Terkait:** `drivers`, `activity_logs`
- **Output (Success 200 OK):**
  ```json
  {
    "message": "Driver successfully deleted."
  }
  ```
- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Data yang Disimpan:**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "DRIVER_DELETED",
      "description": "Admin ([Nama Admin]) menghapus data driver: [Nama Driver].",
      "details": { "adminId": "ID admin", "driverId": "ID driver yang dihapus" }
    }
    ```
  
---

## 5. Manajemen Data Master (CRUD)
Admin harus dapat mengelola semua data master yang digunakan oleh aplikasi. Untuk setiap entitas di bawah ini, diperlukan endpoint CRUD standar (Create, Read, Update, Delete) dengan input dan output berupa objek dari entitas yang bersangkutan.

**Setiap operasi CRUD pada data master akan mencatat log aktivitas.**

-   **Ambulance Types:** `GET, POST, PUT, DELETE /api/admin/ambulance-types`
    - **Tabel Terkait:** `ambulance_types`, `activity_logs`
-   **Purposes (Tujuan Pemesanan):** `GET, POST, PUT, DELETE /api/admin/purposes`
    - **Tabel Terkait:** `purposes`, `activity_logs`
-   **Additional Services (Layanan Tambahan):** `GET, POST, PUT, DELETE /api/admin/additional-services`
    - **Tabel Terkait:** `additional_services`, `activity_logs`
-   **Destinations (Lokasi Umum):** `GET, POST, PUT, DELETE /api/admin/destinations`
    - **Tabel Terkait:** `destinations`, `activity_logs`

- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Data yang Disimpan (contoh untuk CREATE):**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "MASTER_DATA_CREATED",
      "description": "Admin ([Nama Admin]) menambahkan data master [Jenis Data Master]: [Nama Data].",
      "details": { "adminId": "ID admin", "masterDataType": "ambulance_types", "recordId": "ID record baru" }
    }
    ```
  - **Catatan:** `action_type` akan menjadi `MASTER_DATA_UPDATED` atau `MASTER_DATA_DELETED` sesuai dengan operasinya. `description` juga disesuaikan.

---

## 6. Manajemen Konfigurasi Aplikasi

### `GET /api/admin/config`
Melihat semua konfigurasi aplikasi.
- **Tabel Terkait:** `app_config`
- **Output (Success 200 OK):**
  ```json
  [
    { 
      "key": "adminFeePercentage",
      "value": "10", 
      "description": "Persentase biaya admin yang dikenakan sebagai booking fee (dalam %)."
    },
    { 
      "key": "adminTelegramChatId",
      "value": "987654321", 
      "description": "ID Chat Telegram admin untuk menerima notifikasi pesanan."
    }
  ]
  ```

### `PUT /api/admin/config`
Mengubah satu atau lebih nilai konfigurasi.
- **Tabel Terkait:** `app_config`, `activity_logs`
- **Input:**
  ```json
  [
    { "key": "adminFeePercentage", "value": "12" },
    { "key": "adminTelegramChatId", "value": "987654321" }
  ]
  ```
- **Output (Success 200 OK):**
  ```json
  {
    "message": "Configuration updated successfully."
  }
  ```
- **Logging (Data untuk `activity_logs`):**
  - **Tabel:** `activity_logs`
  - **Proses:** Log dibuat untuk setiap `key` yang nilainya berubah.
  - **Data yang Disimpan (per key):**
    ```json
    {
      "actor_type": "admin",
      "actor_id": "ID admin yang sedang login",
      "action_type": "APP_CONFIG_UPDATED",
      "description": "Admin ([Nama Admin]) mengubah konfigurasi: [Key Konfigurasi] dari '[Nilai Lama]' menjadi '[Nilai Baru]'.",
      "details": { "adminId": "ID admin", "configKey": "adminFeePercentage", "oldValue": "10", "newValue": "12" }
    }
    ```

---

## 7. Manajemen Log Aktivitas

### `GET /api/admin/activity-logs`
Melihat riwayat aktivitas yang terjadi di sistem untuk audit dan pemantauan.
- **Tabel Terkait:** `activity_logs`
- **Query Params (opsional untuk filter):**
  - `user_id`: ID pengguna yang melakukan aksi.
  - `driver_id`: ID driver yang terlibat.
  - `order_id`: ID pesanan yang terlibat.
  - `action_type`: Jenis aksi (misal: `ORDER_CREATED`, `PAYMENT_SUCCESS`, `DRIVER_ASSIGNED`).
  - `start_date` / `end_date`: Rentang waktu.
- **Output (Success 200 OK):**
  ```json
  [
    {
      "logId": 1,
      "timestamp": "2024-07-22T10:05:00Z",
      "actor": {
        "type": "admin",
        "id": 1,
        "name": "Admin Super"
      },
      "action": "ORDER_CONFIRMED",
      "description": "Admin (Admin Super) mengonfirmasi pesanan RESQ-123456 dan menugaskan driver Agus Setiawan."
    },
    {
      "logId": 2,
      "timestamp": "2024-07-22T10:00:00Z",
      "actor": {
        "type": "user",
        "id": 12,
        "name": "Budi Santoso"
      },
      "action": "BOOKING_FEE_PAID",
      "description": "Pengguna (Budi Santoso) berhasil membayar booking fee untuk pesanan RESQ-123456."
    }
  ]
  ```

### Definisi `action_type`
Tabel berikut merinci jenis-jenis aksi (`action_type`) yang dicatat dalam log aktivitas untuk memberikan gambaran yang jelas tentang setiap peristiwa yang terjadi di dalam sistem. `action_type` dapat digunakan sebagai filter saat mengambil data log.

| Kategori              | `action_type`                 | Deskripsi Aksi                                                              | Aktor Utama   |
| --------------------- | ----------------------------- | --------------------------------------------------------------------------- | ------------- |
| **Otentikasi**        | `AUTH_LOGIN`                  | Pengguna berhasil masuk ke aplikasi.                                        | User          |
|                       | `AUTH_REGISTER`               | Pengguna baru berhasil mendaftar.                                           | User          |
|                       | `AUTH_GOOGLE_SIGNIN`          | Pengguna berhasil masuk atau mendaftar melalui Google.                      | User          |
|                       | `AUTH_PASSWORD_CHANGE`        | Pengguna mengubah kata sandi mereka.                                        | User          |
| **Profil Pengguna**   | `USER_PROFILE_UPDATED`        | Pengguna memperbarui profilnya (termasuk mendaftarkan FCM token).           | User          |
| **Pesanan Pengguna**  | `ORDER_CREATED`               | Pengguna membuat pesanan baru.                                              | User          |
|                       | `ORDER_REVIEWED`              | Pengguna memberikan rating dan ulasan untuk pesanan yang telah selesai.     | User          |
|                       | `ORDER_CANCELLED_BY_USER`     | Pengguna membatalkan pesanan (jika diizinkan oleh sistem).                  | User          |
| **Pembayaran**        | `PAYMENT_BOOKING_FEE_SUCCESS` | Pembayaran *booking fee* berhasil (via webhook atau konfirmasi manual).     | User / Sistem |
|                       | `PAYMENT_FINAL_SUCCESS`       | Pembayaran sisa tagihan berhasil (via webhook atau konfirmasi manual).      | User / Sistem |
|                       | `PAYMENT_COD_SELECTED`        | Pengguna memilih metode pembayaran Bayar di Tempat (COD).                   | User          |
| **Pesanan Admin**     | `ORDER_CONFIRMED`             | Admin mengonfirmasi pesanan, menambah layanan, dan menugaskan driver.       | Admin         |
|                       | `ORDER_COMPLETED`             | Admin menandai pesanan sebagai selesai.                                     | Admin         |
|                       | `ORDER_CANCELLED_BY_SYSTEM`   | Admin membatalkan pesanan.                                                  | Admin         |
| **Manajemen Driver**  | `DRIVER_CREATED`              | Admin menambahkan data driver baru.                                         | Admin         |
|                       | `DRIVER_UPDATED`              | Admin memperbarui data driver.                                              | Admin         |
|                       | `DRIVER_DELETED`              | Admin menghapus data driver.                                                | Admin         |
| **Manajemen Master**  | `MASTER_DATA_CREATED`         | Admin menambahkan data master baru (jenis ambulans, layanan, dll).          | Admin         |
|                       | `MASTER_DATA_UPDATED`         | Admin memperbarui data master.                                              | Admin         |
|                       | `MASTER_DATA_DELETED`         | Admin menghapus data master.                                                | Admin         |
| **Manajemen Sistem**  | `APP_CONFIG_UPDATED`          | Admin mengubah satu atau lebih nilai konfigurasi aplikasi.                  | Admin         |

---

## 8. Manajemen Chat
Bagian ini mendefinisikan API untuk fitur chat dari sisi admin.

### `GET /api/admin/chat/rooms`
Melihat daftar semua sesi chat.
- **Tabel Terkait:** `chat_rooms`, `chat_messages`
- **Query Params (opsional):**
  - `status`: 'unread' | 'all'
- **Output (Success 200 OK):**
  ```json
  [
    {
      "roomId": 1,
      "userName": "Budi Santoso",
      "orderNumber": "RESQ-123456",
      "lastMessage": "Nomor pesanan saya RESQ-123456.",
      "lastMessageTimestamp": "2024-07-23T10:33:00Z",
      "hasUnread": true
    }
  ]
  ```

### `POST /api/admin/chat/rooms/{roomId}/messages`
Admin mengirim pesan ke sebuah sesi chat.
- **Tabel Terkait:** `chat_messages`
- **Proses:**
  1. Backend menyimpan pesan ke database.
  2. Backend mengirim notifikasi real-time (via WebSocket) ke pengguna.
- **Input:**
  ```json
  {
    "messageText": "Baik, Budi. Pesanan Anda sedang kami periksa."
  }
  ```
- **Output (Success 201 Created):**
  ```json
  {
    "id": 4,
    "text": "Baik, Budi. Pesanan Anda sedang kami periksa.",
    "sender": { "id": 1, "type": "admin", "name": "Admin Super" },
    "timestamp": "2024-07-23T10:34:00Z"
  }
  ```

---

## 9. Definisi Skema & Model Data

Struktur tabel database, tipe data, relasi, dan indeks yang direkomendasikan telah didefinisikan secara lengkap dalam file `schema.sql`. Dokumen tersebut menjadi satu-satunya sumber kebenaran (single source of truth) untuk skema database.

Pastikan implementasi backend mengacu pada file `schema.sql` untuk menjaga konsistensi data.
