<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Driver - Ambulans</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Use Inter as the default font */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom styles for file input */
        .file-input-label {
            cursor: pointer;
            border-width: 2px;
            border-style: dashed;
            transition: background-color 0.2s, border-color 0.2s;
        }
        .file-input-label:hover {
            background-color: #fef2f2; /* red-50 */
            border-color: #ef4444; /* red-500 */
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h1 class="text-2xl font-bold text-gray-800">Ambulans</h1>
            </a>
            <div class="hidden md:flex items-center space-x-8">
                <!-- Dropdown Menu -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button @click="open = !open" class="flex items-center transition-colors duration-300 focus:outline-none" :class="!atTop ? 'text-gray-800 hover:text-red-600' : 'text-gray-600 hover:text-red-600'">
                        Partner with Us
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20 py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">Driver Partner</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">Ambulance Provider</a>
                    </div>
                </div>
                <a href="/#benefits" class="transition-colors duration-300" :class="!atTop ? 'text-gray-800 hover:text-red-600' : 'text-gray-600 hover:text-red-600'">Benefits</a>
                <a href="/#faq" class="transition-colors duration-300" :class="!atTop ? 'text-gray-800 hover:text-red-600' : 'text-gray-600 hover:text-red-600'">FAQ</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-12 md:py-20">
        <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-4">Formulir Pendaftaran Driver</h2>
            <p class="text-gray-600 text-center mb-10">Lengkapi data diri dan unggah dokumen yang diperlukan untuk menjadi mitra kami.</p>

            <form action="{{ route('driver-registration') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-8">
                    <!-- Section: Informasi Pribadi -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-6">Informasi Pribadi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" placeholder="Asep..." name="full_name" id="full_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <input type="tel" placeholder="08xxxxx" name="phone_number" id="phone_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Unggah Dokumen -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-6">Unggah Dokumen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">

                            <!-- KTP -->
                            <div x-data="{ fileName: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">KTP</label>
                                <label for="ktp_file" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="!fileName">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileName" x-text="fileName"></p>
                                    </div>
                                    <input id="ktp_file" name="ktp_file" type="file" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="sr-only" required>
                                </label>
                            </div>

                            <!-- Pas Foto 10x8 -->
                            <div x-data="{ fileName: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pas Foto 10x8</label>
                                <label for="photo_file" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="!fileName">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileName" x-text="fileName"></p>
                                    </div>
                                    <input id="photo_file" name="photo_file" type="file" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="sr-only" required>
                                </label>
                            </div>

                            <!-- SIM -->
                            <div x-data="{ fileName: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">SIM (Surat Izin Mengemudi)</label>
                                <label for="sim_file" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="!fileName">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileName" x-text="fileName"></p>
                                    </div>
                                    <input id="sim_file" name="sim_file" type="file" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="sr-only" required>
                                </label>
                            </div>

                            <!-- STNK -->
                            <div x-data="{ fileName: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">STNK (Surat Tanda Nomor Kendaraan)</label>
                                <label for="stnk_file" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="!fileName">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileName" x-text="fileName"></p>
                                    </div>
                                    <input id="stnk_file" name="stnk_file" type="file" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="sr-only" required>
                                </label>
                            </div>
                            
                            <!-- Foto Unit Luar & Dalam -->
                            <div x-data="{ fileCount: 0 }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Unit (Luar & Dalam)</label>
                                <label for="unit_photos" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="fileCount === 0">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileCount > 0"><span x-text="fileCount"></span> file dipilih</p>
                                    </div>
                                    <input id="unit_photos" name="unit_photos" type="file" @change="fileCount = $event.target.files.length" class="sr-only" multiple required>
                                </label>
                            </div>

                            <!-- Foto Kelengkapan Ambulans -->
                            <div x-data="{ fileCount: 0 }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Kelengkapan Ambulans</label>
                                <label for="equipment_photos" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="fileCount === 0">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileCount > 0"><span x-text="fileCount"></span> file dipilih</p>
                                    </div>
                                    <input id="equipment_photos" name="equipment_photos" type="file" @change="fileCount = $event.target.files.length" class="sr-only" multiple required>
                                </label>
                            </div>

                            <!-- Sertifikasi EVOC/AVOC -->
                            <div x-data="{ fileName: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sertifikasi EVOC/AVOC</label>
                                <label for="evoc_cert" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="!fileName">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileName" x-text="fileName"></p>
                                    </div>
                                    <input id="evoc_cert" name="evoc_cert" type="file" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="sr-only" required>
                                </label>
                            </div>

                            <!-- Sertifikasi Pelatihan Medis -->
                            <div x-data="{ fileName: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sertifikasi Pelatihan Medis</label>
                                <label for="medical_cert" class="file-input-label flex justify-center w-full px-6 py-10 border-gray-300 rounded-md">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <p class="mt-1 text-sm text-gray-600" x-show="!fileName">Klik untuk mengunggah</p>
                                        <p class="mt-1 text-sm font-semibold text-red-600" x-show="fileName" x-text="fileName"></p>
                                    </div>
                                    <input id="medical_cert" name="medical_cert" type="file" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="sr-only" required>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-12 pt-8 border-t">
                    <div class="flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-red-600 text-white font-bold py-3 px-10 rounded-lg hover:bg-red-700 transition duration-300 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Kirim Pendaftaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-10 mt-20">
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
