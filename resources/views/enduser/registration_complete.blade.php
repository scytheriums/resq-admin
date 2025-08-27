<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Ambulans</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Use Inter as the default font */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Header Navigation -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h1 class="text-2xl font-bold text-gray-800">Ambulans</h1>
            </a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 py-12 md:py-20 flex items-center justify-center">
        <div class="max-w-2xl w-full bg-white p-8 md:p-12 rounded-lg shadow-lg text-center">
            
            <!-- Animated Success Icon -->
            <div class="mx-auto w-36 h-36 flex items-center justify-center mb-6 overflow-hidden">
                <img src="{{ asset('assets/img/complete.png') }}" alt="Registration Successful Animation" class="w-full h-full object-cover">
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-4">Pendaftaran Berhasil!</h2>
            
            <p class="text-gray-600 mb-8">
                Terima kasih telah mendaftar untuk menjadi mitra Ambulans. Data dan dokumen Anda telah kami terima.
            </p>

            <div class="bg-gray-100 p-6 rounded-lg text-left">
                <h3 class="font-semibold text-lg text-gray-800 mb-3">Langkah Selanjutnya:</h3>
                <p class="text-gray-600">
                    Tim kami akan segera meninjau dokumen Anda. Proses verifikasi biasanya memakan waktu <strong>3-5 hari kerja</strong>. Kami akan menghubungi Anda melalui nomor telepon yang terdaftar untuk memberikan informasi lebih lanjut.
                </p>
            </div>

            <!-- Back to Home Button -->
            <div class="mt-10">
                <a href="/" class="w-full md:w-auto bg-red-600 text-white font-bold py-3 px-10 rounded-lg hover:bg-red-700 transition duration-300 shadow-lg">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-10">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 Ambulans. All Rights Reserved.</p>
            <div class="flex justify-center space-x-6 mt-4">
                <a href="#" class="hover:text-red-500 transition duration-300">Privacy Policy</a>
                <a href="#" class="hover:text-red-500 transition duration-300">Terms of Service</a>
                <a href="#" class="hover:text-red-500 transition duration-300">Contact Us</a>
            </div>
        </div>
    </footer>

</body>
</html>
