<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambulans Driver Portal - Join Our Team</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN for interactivity -->
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
        /* Enable smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header Navigation -->
    <header x-data="{ atTop: window.pageYOffset <= 50 }" @scroll.window="atTop = window.pageYOffset <= 50" 
            class="sticky top-0 z-50 transition-all duration-300"
            :class="!atTop ? 'bg-white bg-opacity-85': 'bg-white shadow-sm'">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8 transition-colors duration-300" :class="!atTop ? 'text-red-600' : 'text-red-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h1 class="text-2xl font-bold transition-colors duration-300" :class="!atTop ? 'text-gray-800' : 'text-gray-800'">Ambulans.co</h1>
            </div>
            <!-- Navigation Links -->
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
                        <a href="/driver-registration" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">Driver Partner</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">Ambulance Provider</a>
                    </div>
                </div>
                <a href="#benefits" class="transition-colors duration-300" :class="!atTop ? 'text-gray-800 hover:text-red-600' : 'text-gray-600 hover:text-red-600'">Benefits</a>
                <a href="#faq" class="transition-colors duration-300" :class="!atTop ? 'text-gray-800 hover:text-red-600' : 'text-gray-600 hover:text-red-600'">FAQ</a>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-red-600 to-red-700 text-white overflow-hidden">
            <div class="container mx-auto px-6 py-20 lg:py-32 relative z-10">
                <!-- Content -->
                <div class="lg:w-1/2 text-center lg:text-left">
                    <h2 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
                        Be a <span class="text-red-300">Hero</span> on Wheels.
                    </h2>
                    <p class="text-lg text-red-100 mb-8 max-w-lg mx-auto lg:mx-0">
                        Join the Ambulans driver network and make a real impact in your community. Provide critical transport when it matters most.
                    </p>
                    <a href="#" class="bg-white text-red-600 font-bold py-4 px-8 rounded-lg text-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-xl">
                        Start Your Application
                    </a>
                </div>
            </div>
             <!-- Ambulance Vehicle Graphic -->
            <div class="absolute bottom-0 right-0 w-full lg:w-1/2 h-full">
                 <img src="{{ asset('assets/img/ambulance.png') }}" alt="Ambulans Hub" class="absolute bottom-0 right-36 w-auto h-64 md:h-96 lg:h-auto lg:max-w-2xl transform lg:translate-x-1/4 opacity-20 lg:opacity-100">
            </div>
        </section>

        <!-- Why Partner With Us? Section -->
        <section id="benefits" class="bg-gray-50 py-20">
            <div class="container mx-auto px-6 text-center">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Why Partner With Us?</h3>
                <p class="text-gray-600 mb-12 max-w-2xl mx-auto">We provide our partners with the tools and support they need to succeed and make a difference.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Feature 1: Flexible Partnership -->
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                             <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Flexible Partnership</h4>
                        <p class="text-gray-600">Choose a model that fits your goals, whether you're an independent driver or a fleet provider.</p>
                    </div>
                    <!-- Feature 2: Lucrative Opportunities -->
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Lucrative Opportunities</h4>
                        <p class="text-gray-600">Access a steady stream of requests to grow your income or business. We offer competitive rates.</p>
                    </div>
                    <!-- Feature 3: Community Impact -->
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Community Impact</h4>
                        <p class="text-gray-600">Your partnership matters. Be a vital part of the healthcare system in your city.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="bg-white py-20">
            <div class="container mx-auto px-6">
                <h3 class="text-3xl font-bold text-gray-800 text-center mb-12">Getting Started is Easy</h3>
                <div class="flex flex-col md:flex-row justify-center items-center md:space-x-10 lg:space-x-20">
                    <!-- Step 1 -->
                    <div class="flex items-center flex-col text-center mb-10 md:mb-0">
                        <div class="flex items-center justify-center w-24 h-24 border-4 border-red-500 rounded-full text-red-500 text-4xl font-bold mb-4">1</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Sign Up Online</h4>
                        <p class="text-gray-600 max-w-xs">Fill out our simple registration form with your details and vehicle information.</p>
                    </div>
                    <!-- Connector -->
                    <div class="hidden md:block h-1 w-20 bg-gray-300"></div>
                    <!-- Step 2 -->
                    <div class="flex items-center flex-col text-center mb-10 md:mb-0">
                        <div class="flex items-center justify-center w-24 h-24 border-4 border-red-500 rounded-full text-red-500 text-4xl font-bold mb-4">2</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Get Verified</h4>
                        <p class="text-gray-600 max-w-xs">Upload your documents. We'll run a quick background check for safety.</p>
                    </div>
                    <!-- Connector -->
                    <div class="hidden md:block h-1 w-20 bg-gray-300"></div>
                    <!-- Step 3 -->
                    <div class="flex items-center flex-col text-center">
                        <div class="flex items-center justify-center w-24 h-24 border-4 border-red-500 rounded-full text-red-500 text-4xl font-bold mb-4">3</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Start Driving</h4>
                        <p class="text-gray-600 max-w-xs">Once approved, you'll get access to the app and can start accepting trips.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="bg-gray-50 py-20">
            <div class="container mx-auto px-6">
                <h3 class="text-3xl font-bold text-gray-800 text-center mb-12">Frequently Asked Questions</h3>
                <div class="max-w-3xl mx-auto">
                    <!-- FAQ Item 1 -->
                    <div x-data="{ open: false }" class="border-b border-gray-200">
                        <button @click="open = !open" class="w-full text-left py-6 px-4 focus:outline-none">
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-gray-800">What are the requirements to become a driver partner?</h4>
                                <svg class="w-6 h-6 text-red-600 transform transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        <div x-show="open" x-collapse class="pb-6 px-4">
                            <p class="text-gray-600">Driver partners must be at least 21 years old, have a valid driver's license, proof of vehicle registration and insurance, and pass a background check. Your vehicle must also meet our safety standards.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 2 -->
                    <div x-data="{ open: false }" class="border-b border-gray-200">
                        <button @click="open = !open" class="w-full text-left py-6 px-4 focus:outline-none">
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-gray-800">How does my ambulance service partner with Ambulans?</h4>
                                <svg class="w-6 h-6 text-red-600 transform transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        <div x-show="open" x-collapse class="pb-6 px-4">
                            <p class="text-gray-600">Ambulance providers can partner with us to expand their reach and optimize fleet utilization. Please contact our partnerships team through the "Ambulance Provider" link to discuss a tailored solution for your organization.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 3 -->
                    <div x-data="{ open: false }" class="border-b border-gray-200">
                        <button @click="open = !open" class="w-full text-left py-6 px-4 focus:outline-none">
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-gray-800">How and when do I get paid?</h4>
                                <svg class="w-6 h-6 text-red-600 transform transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        <div x-show="open" x-collapse class="pb-6 px-4">
                            <p class="text-gray-600">Payments are calculated weekly and transferred directly to your bank account. You can track your earnings in real-time through the driver app.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="bg-red-600 text-white py-20">
            <div class="container mx-auto px-6 text-center">
                <img src="https://placehold.co/100x100/ffffff/ef4444?text=Driver" alt="Photo of a driver" class="w-24 h-24 rounded-full mx-auto mb-6 border-4 border-white">
                <p class="text-2xl italic mb-4 max-w-3xl mx-auto">"Driving for Ambulans has been incredibly rewarding. I set my own hours, earn a good income, and I'm proud of the work I do every day."</p>
                <p class="font-bold text-lg">- Maria S., Ambulans Driver</p>
            </div>
        </section>
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
