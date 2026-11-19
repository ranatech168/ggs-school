<!doctype html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::valueFor('school_name', 'Global Generation School') }} - {{ \App\Models\Setting::valueFor('tagline', 'Siem Reap') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toastr Notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Google Fonts: Kantumruy Pro & Battambang for Khmer, Inter for English -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700;900&family=Inter:wght@300;400;500;600;700;800;900&family=Kantumruy+Pro:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ggsNavy: '#0B2240',
                        ggsNavyLight: '#14355E',
                        ggsNavyDark: '#07162B',
                        ggsGold: '#F5A623',
                        ggsGoldHover: '#DF9312',
                        ggsBlueAccent: '#1E6091',
                        ggsLightBg: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Kantumruy Pro', 'Inter', 'sans-serif'],
                        battambang: ['Battambang', 'sans-serif'],
                        english: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Custom Site CSS for subpages & typography -->
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">

    <style>
        :root {
            font-size: 15.5px;
        }
        @media (min-width: 768px) {
            :root {
                font-size: 16px;
            }
        }
        .hero-overlay {
            background: linear-gradient(135deg, rgba(11, 34, 64, 0.40) 0%, rgba(7, 22, 43, 0.20) 45%, rgba(11, 34, 64, 0.50) 100%),
                        linear-gradient(to top, rgba(7, 22, 43, 0.70) 0%, transparent 55%);
        }
        .hero-title-shadow {
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.90), 0 4px 28px rgba(0, 0, 0, 0.75);
        }
        .hero-body-shadow {
            text-shadow: 0 1px 8px rgba(0, 0, 0, 0.90), 0 3px 18px rgba(0, 0, 0, 0.70);
        }
        .khmer-title-glow {
            text-shadow: 0 2px 10px rgba(245, 166, 35, 0.20);
        }
        .font-battambang {
            line-height: 1.35;
        }
        .font-sans {
            line-height: 1.65;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 9px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #0B2240;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #F5A623;
        }
    </style>
</head>
<body class="font-sans text-slate-800 bg-ggsLightBg text-base antialiased selection:bg-ggsGold selection:text-white relative">

    <!-- TOP ANNOUNCEMENT & CONTACT BAR -->
    <div class="bg-ggsNavyDark text-slate-200 text-xs sm:text-sm py-2.5 px-4 sm:px-8 border-b border-white/10 shadow-sm">
        <div class="w-full px-2 sm:px-4 lg:px-6 flex flex-col md:flex-row justify-between items-center gap-2.5">
            <div class="flex items-center space-x-4 sm:space-x-5 flex-wrap justify-center font-medium">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-phone text-ggsGold text-sm"></i> 
                    <a href="tel:{{ \App\Models\Setting::valueFor('contact_phone', '0886666288') }}" class="hover:text-ggsGold transition-colors tracking-wide">088 6666 288</a>
                    <span class="text-slate-500">/</span>
                    <a href="tel:012452837" class="hover:text-ggsGold transition-colors tracking-wide">012 452 837</a>
                    <span class="text-slate-500">/</span>
                    <a href="tel:092820249" class="hover:text-ggsGold transition-colors tracking-wide">092 820 249</a>
                </span>
                <span class="hidden sm:inline-block text-slate-600">|</span>
                <a href="https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox?compose=new" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 hover:text-ggsGold transition-colors group cursor-pointer" title="Send email to {{ \App\Models\Setting::valueFor('contact_email', 'ggschool2020@gmail.com') }} via Gmail">
                    <i class="fa-solid fa-envelope text-ggsGold text-sm group-hover:scale-110 transition-transform"></i> 
                    <span>{{ \App\Models\Setting::valueFor('contact_email', 'ggschool2020@gmail.com') }}</span>
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="https://www.facebook.com/globalgenerationschool" target="_blank" class="hover:text-ggsGold transition-colors flex items-center gap-2" title="Official Facebook">
                    <i class="fa-brands fa-facebook text-base text-ggsGold"></i> 
                    <span class="font-bold text-xs">@globalgenerationschool</span>
                </a>
                
                <span class="text-slate-600">|</span>
                
                <!-- DUAL LANGUAGE SWITCHER BUTTONS -->
                <div class="flex items-center bg-white/10 rounded-full p-1 border border-white/20">
                    <button id="btn-lang-km" onclick="setLanguage('km')" class="px-3 py-0.5 rounded-full text-xs font-bold {{ app()->getLocale() === 'km' ? 'bg-ggsGold text-ggsNavy shadow-sm' : 'text-slate-300 hover:text-white' }} transition-all cursor-pointer">
                        ខ្មែរ
                    </button>
                    <button id="btn-lang-en" onclick="setLanguage('en')" class="px-3 py-0.5 rounded-full text-xs font-bold {{ app()->getLocale() !== 'km' ? 'bg-ggsGold text-ggsNavy shadow-sm' : 'text-slate-300 hover:text-white' }} transition-all cursor-pointer">
                        English
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-200/80">
        <div class="w-full px-4 sm:px-8 lg:px-12 h-20 sm:h-22 flex items-center justify-between gap-4">
            
            <!-- Logo Section -->
            <a href="{{ route('home') }}" class="flex items-center gap-3.5 group shrink-0">
                <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-ggsNavy flex items-center justify-center text-ggsGold font-black text-xl sm:text-2xl shadow-md border-2 border-ggsGold group-hover:scale-105 transition-transform">
                    GGS
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-xl sm:text-2xl text-ggsNavy leading-tight tracking-wide font-battambang">{{ \App\Models\Setting::valueFor('school_name', 'សាលាជំនាន់សកល') }}</span>
                    <span class="text-[11px] sm:text-xs font-bold text-ggsGold tracking-wider uppercase font-english">Global Generation School</span>
                </div>
            </a>

            <!-- Center Navigation & Book Campus Tour -->
            <div class="hidden xl:flex items-center space-x-6 text-[15px] font-bold text-slate-700">
                <nav class="flex items-center space-x-6">
                    <a href="{{ route('home') }}#home" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទំព័រដើម</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Home</span>
                    </a>
                    <a href="{{ route('home') }}#why-ggs" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ហេតុអ្វី GGS?</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Why GGS?</span>
                    </a>
                    <a href="{{ route('home') }}#pillars" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សសរស្តម្ភទាំង៦</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">6 Pillars</span>
                    </a>
                    <a href="{{ route('home') }}#accelerated" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ថ្នាក់ពន្លឿន</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Accelerated</span>
                    </a>
                    <a href="{{ route('home') }}#service-learning" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">បម្រើសហគមន៍</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Service</span>
                    </a>
                    <a href="{{ route('home') }}#branches" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សាខា</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Branches</span>
                    </a>
                    <a href="{{ route('news') }}" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ព័ត៌មាន</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">News</span>
                    </a>
                    <a href="{{ route('home') }}#child-protection" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សុវត្ថិភាព</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Child Safety</span>
                    </a>
                    <a href="{{ route('home') }}#contact-section" class="hover:text-ggsGold transition-colors py-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទំនាក់ទំនង</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Contact</span>
                    </a>
                </nav>

                <button onclick="openInquiryModal()" class="px-5 py-2.5 bg-ggsGold hover:bg-ggsGoldHover text-ggsNavy font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5 flex items-center gap-2 text-xs sm:text-sm uppercase tracking-wider cursor-pointer shrink-0">
                    <i class="fa-regular fa-calendar-check text-sm"></i>
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចុះឈ្មោះទស្សនាសាលា</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Book Campus Tour</span>
                </button>
            </div>

            <!-- Far Right: Login / Dashboard Button (At the highlight at the right side) -->
            <div class="flex items-center shrink-0">
                <a href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}" 
                   class="px-4 py-2.5 bg-slate-100 hover:bg-ggsNavy text-slate-800 hover:text-white font-bold rounded-xl border border-slate-300 shadow-sm transition-all flex items-center gap-2 text-xs sm:text-sm group"
                   title="{{ auth()->check() ? 'Admin Dashboard' : 'Staff & Admin Login' }}">
                    <i class="fa-solid {{ auth()->check() ? 'fa-gauge text-ggsGold' : 'fa-right-to-bracket text-ggsGold' }} text-xs group-hover:scale-110 transition-transform"></i>
                    <span class="tracking-wide">{{ auth()->check() ? 'Dashboard' : 'Login' }}</span>
                </a>

                <!-- Mobile Menu Trigger -->
                <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="xl:hidden ml-3 text-ggsNavy hover:text-ggsGold p-2 focus:outline-none">
                    <i class="fa-solid fa-bars text-3xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div id="mobile-menu" class="hidden xl:hidden bg-white border-b border-slate-200 px-6 py-6 space-y-4 shadow-2xl">
            <!-- Mobile Language Switcher -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">ភាសា / Language</span>
                <div class="flex items-center bg-slate-100 rounded-full p-1 border border-slate-200">
                    <button onclick="setLanguage('km')" class="px-4 py-1 rounded-full text-xs font-bold {{ app()->getLocale() === 'km' ? 'bg-ggsGold text-ggsNavy shadow-sm' : 'text-slate-600' }} transition-all cursor-pointer">
                        ខ្មែរ
                    </button>
                    <button onclick="setLanguage('en')" class="px-4 py-1 rounded-full text-xs font-bold {{ app()->getLocale() !== 'km' ? 'bg-ggsGold text-ggsNavy shadow-sm' : 'text-slate-600' }} transition-all cursor-pointer">
                        English
                    </button>
                </div>
            </div>

            <a href="{{ route('home') }}#home" onclick="toggleMobileMenu()" class="block font-bold text-lg text-ggsNavy py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទំព័រដើម</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Home</span>
            </a>
            <a href="{{ route('home') }}#why-ggs" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ហេតុអ្វីជ្រើសរើស GGS?</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Why GGS?</span>
            </a>
            <a href="{{ route('home') }}#pillars" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សសរស្តម្ភយុទ្ធសាស្ត្រទាំង៦</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">6 Strategic Pillars</span>
            </a>
            <a href="{{ route('home') }}#accelerated" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">កម្មវិធីសិក្សាពន្លឿន</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Accelerated Program</span>
            </a>
            <a href="{{ route('home') }}#service-learning" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">អប់រំបម្រើសហគមន៍ (ថ្នាក់ទី៦)</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Grade 6 Service Learning</span>
            </a>
            <a href="{{ route('home') }}#branches" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សាខាសាលារៀន</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">School Branches</span>
            </a>
            <a href="{{ route('news') }}" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ព័ត៌មាន & អត្ថបទ</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">News & Stories</span>
            </a>
            <a href="{{ route('home') }}#child-protection" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សុវត្ថិភាព & ការពារកុមារ</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Child Protection</span>
            </a>
            <a href="{{ route('home') }}#contact-section" onclick="toggleMobileMenu()" class="block font-semibold text-base text-slate-700 py-2.5 border-b border-slate-100">
                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទំនាក់ទំនងទីតាំង</span>
                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Campus Contact</span>
            </a>
            <div class="pt-3 flex flex-col gap-2.5">
                <button onclick="openInquiryModal(); toggleMobileMenu();" class="w-full py-3.5 bg-ggsGold text-ggsNavy font-black rounded-xl shadow-md text-center flex items-center justify-center gap-2.5 text-base cursor-pointer">
                    <i class="fa-regular fa-calendar-check text-lg"></i>
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចុះឈ្មោះទស្សនាសាលារៀន</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Book Campus Tour</span>
                </button>
                <a href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}" class="w-full py-3 bg-ggsNavy hover:bg-slate-800 text-ggsGold font-bold rounded-xl shadow-sm text-center flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid {{ auth()->check() ? 'fa-gauge' : 'fa-right-to-bracket' }} text-xs"></i>
                    <span>{{ auth()->check() ? 'Admin Dashboard' : 'Admin Login' }}</span>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENT WRAPPER -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer id="contact" class="bg-ggsNavyDark text-slate-300 pt-16 pb-10 border-t border-white/10">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 pb-12 border-b border-white/10">
                
                <!-- Col 1: School Identity -->
                <div class="lg:col-span-5 space-y-5">
                    <div class="flex items-center gap-3.5">
                        <div class="w-13 h-13 rounded-2xl bg-ggsNavy text-ggsGold font-black text-2xl flex items-center justify-center border-2 border-ggsGold shadow-lg">
                            GGS
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-xl sm:text-2xl font-battambang">{{ \App\Models\Setting::valueFor('school_name', 'សាលាជំនាន់សកល') }}</h4>
                            <p class="text-xs sm:text-sm text-ggsGold uppercase font-bold tracking-wider">Global Generation School (Siem Reap)</p>
                        </div>
                    </div>
                    
                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-lg">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ផ្តល់ជូននូវការអប់រំបៃតង ឌីជីថល ភាសាអង់គ្លេស STEM និងបំណិនជីវិតពិតប្រាកដ ដើម្បីរៀបចំសិស្សកម្ពុជាឱ្យក្លាយជាពលរដ្ឋសកល។</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Inspiring Cambodian youth through bilingual excellence, STEM innovation, AI skills, and patriotic character.</span>
                    </p>

                    <div class="pt-1 flex flex-wrap items-center gap-3">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-ggsGold text-xs sm:text-sm font-bold border border-ggsGold/30">
                            "បេះដូងស្នេហាជាតិ គំនិតកម្រិតសកល"
                        </span>
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm text-slate-400 hover:text-ggsGold transition-colors inline-flex items-center gap-1.5 font-medium ml-2">
                            <i class="fa-solid fa-lock text-xs"></i> Staff Portal
                        </a>
                    </div>
                </div>

                <!-- Col 2: Direct Contact Info -->
                <div class="lg:col-span-4 space-y-4 text-sm sm:text-base">
                    <h5 class="text-white font-bold text-base sm:text-lg mb-3 border-b border-ggsGold/40 pb-2.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ព័ត៌មានទំនាក់ទំនងទីតាំង</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Campus Contact Details</span>
                    </h5>

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-ggsGold text-base mt-1 shrink-0"></i>
                        <div>
                            <span class="font-bold text-white text-sm sm:text-base">អាសយដ្ឋានទីតាំង៖</span>
                            <p class="text-slate-300 mt-0.5 text-xs sm:text-sm leading-relaxed">ភូមិសាលាកំរើក ក្រុងសៀមរាប ខេត្តសៀមរាប (Sala Kamreauk Village, Siem Reap City, Cambodia)</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-ggsGold text-sm shrink-0"></i>
                        <div>
                            <span class="font-bold text-white text-xs sm:text-sm">លេខទូរស័ព្ទ៖</span>
                            <span class="text-slate-200 font-semibold text-xs sm:text-sm ml-1.5">012 452 837 / 092 820 249 / 088 666 6288</span>
                        </div>
                    </div>

                    <a href="https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox?compose=new" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 text-slate-200 hover:text-ggsGold transition-colors group cursor-pointer" title="Send email to {{ \App\Models\Setting::valueFor('contact_email', 'ggschool2020@gmail.com') }} via Gmail">
                        <i class="fa-solid fa-envelope text-ggsGold text-sm shrink-0 group-hover:scale-110 transition-transform"></i>
                        <div>
                            <span class="font-bold text-white text-xs sm:text-sm">អ៊ីមែល៖</span>
                            <span class="font-semibold text-xs sm:text-sm ml-1.5">{{ \App\Models\Setting::valueFor('contact_email', 'ggschool2020@gmail.com') }}</span>
                        </div>
                    </a>

                    <div class="flex items-center gap-3 pt-1">
                        <a href="https://www.facebook.com/globalgenerationschool" target="_blank" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs sm:text-sm flex items-center gap-2.5 transition-all shadow-md">
                            <i class="fa-brands fa-facebook text-sm"></i> @globalgenerationschool
                        </a>
                    </div>
                </div>

                <!-- Col 3: Quick Navigation -->
                <div class="lg:col-span-3 space-y-3 text-xs sm:text-sm">
                    <h5 class="text-white font-bold text-base sm:text-lg mb-3 border-b border-ggsGold/40 pb-2.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">តំណភ្ជាប់រហ័ស</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Quick Links</span>
                    </h5>
                    <ul class="space-y-2.5 font-medium">
                        <li><a href="{{ route('home') }}#home" class="hover:text-ggsGold transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ggsGold"></i> ទំព័រដើម (Home)</a></li>
                        <li><a href="{{ route('home') }}#why-ggs" class="hover:text-ggsGold transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ggsGold"></i> ហេតុអ្វី GGS? (Why GGS?)</a></li>
                        <li><a href="{{ route('home') }}#pillars" class="hover:text-ggsGold transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ggsGold"></i> សសរស្តម្ភទាំង៦ (6 Pillars)</a></li>
                        <li><a href="{{ route('home') }}#accelerated" class="hover:text-ggsGold transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ggsGold"></i> ថ្នាក់ពន្លឿន (Accelerated Program)</a></li>
                        <li><a href="{{ route('home') }}#service-learning" class="hover:text-ggsGold transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ggsGold"></i> អប់រំបម្រើសហគមន៍ (Service Learning)</a></li>
                        <li><a href="{{ route('news') }}" class="hover:text-ggsGold transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ggsGold"></i> ព័ត៌មាន (News & Updates)</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 text-center text-xs sm:text-sm text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div>
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::valueFor('school_name', 'Global Generation School') }} (GGS). All Rights Reserved.
                </div>
                <div class="flex items-center space-x-5 text-xs sm:text-sm font-medium">
                    <a href="{{ route('home') }}#child-protection" class="hover:text-ggsGold transition-colors">Child Safety Policy</a>
                    <span>•</span>
                    <a href="{{ route('home') }}#contact-section" class="hover:text-ggsGold transition-colors">Siem Reap Campus</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- TOUR & INQUIRY MODAL -->
    <div id="inquiryModal" class="fixed inset-0 bg-black/65 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-7 sm:p-9 shadow-2xl relative border border-slate-100 max-h-[95vh] overflow-y-auto">
            <button onclick="closeInquiryModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 w-9 h-9 rounded-full flex items-center justify-center bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
            
            <div class="text-center mb-6">
                <div class="w-13 h-13 bg-ggsNavy/10 text-ggsNavy rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                    <i class="fa-regular fa-calendar-check text-ggsGold"></i>
                </div>
                <h3 class="font-battambang text-2xl sm:text-3xl font-extrabold text-ggsNavy">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចុះឈ្មោះទស្សនាសាលារៀន GGS</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Book a Campus Tour</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សូមបំពេញព័ត៌មានខាងក្រោម ក្រុមការងារយើងខ្ញុំនឹងទាក់ទងទៅលោកអ្នកភ្លាមៗ</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Please fill out the form below. Our team will contact you shortly.</span>
                </p>
            </div>
            
            <form id="inquiry-form" onsubmit="handleModalSubmit(event)" class="space-y-4 text-sm">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ឈ្មោះអាណាព្យាបាល (Parent Name) *</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Parent Full Name *</span>
                    </label>
                    <input type="text" id="inquiry-name" name="name" required placeholder="ឧទាហរណ៍៖ សុខ ចាន់ថន" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm sm:text-base">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">អ៊ីមែល (Email) *</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Email Address *</span>
                    </label>
                    <input type="email" id="inquiry-email" name="email" required placeholder="example@email.com" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm sm:text-base">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">លេខទូរស័ព្ទ / Telegram</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Phone / Telegram Number</span>
                    </label>
                    <input type="tel" id="inquiry-phone" name="phone" placeholder="012 345 678" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm sm:text-base">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សារបន្ថែម ឬ ថ្ងៃខែចង់មកទស្សនា</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Additional Note / Preferred Date</span>
                    </label>
                    <textarea id="inquiry-message" name="message" rows="2" placeholder="ព័ត៌មានលម្អិត..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm sm:text-base"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-ggsGold hover:bg-ggsGoldHover text-ggsNavy font-bold rounded-xl shadow-md transition-colors text-sm sm:text-base uppercase tracking-wider cursor-pointer mt-2">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">បញ្ជូនសំណើទស្សនាសាលា</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Confirm Tour Request</span>
                </button>
            </form>
        </div>
    </div>

    <!-- POST NEWS MODAL (FRONT END) -->
    <div id="newsPostingModal" class="fixed inset-0 bg-black/65 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100 max-h-[92vh] overflow-y-auto">
            <button onclick="closeNewsModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 w-9 h-9 rounded-full flex items-center justify-center bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
            
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-amber-50 text-ggsGold rounded-2xl flex items-center justify-center mx-auto mb-2.5 text-xl font-bold border border-amber-200">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <h3 class="font-battambang text-2xl sm:text-3xl font-extrabold text-ggsNavy">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចុះផ្សាយព័ត៌មានសាលារៀន</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Post School News</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចែករំលែកសកម្មភាព ព្រឹត្តិការណ៍ និងសមិទ្ធផលថ្មីៗលើគេហទំព័រ</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Publish articles, campus events, and stories directly to the school website.</span>
                </p>
            </div>
            
            <form id="news-posting-form" onsubmit="handleNewsSubmit(event)" enctype="multipart/form-data" class="space-y-4 text-sm">
                @csrf
                
                <!-- Title EN & KM -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Title (English) *
                    </label>
                    <input type="text" id="news-title-en" name="title[en]" required placeholder="e.g. Annual STEM Fair and Student Exhibition" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        ចំណងជើងព័ត៌មាន (Khmer)
                    </label>
                    <input type="text" id="news-title-km" name="title[km]" placeholder="ឧទាហរណ៍៖ ពិព័រណ៍វិទ្យាសាស្ត្រ STEM ប្រចាំឆ្នាំ និងការបង្ហាញស្នាដៃសិស្ស" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm">
                </div>

                <!-- Campus Branch Selection -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Campus Branch (Optional)
                    </label>
                    <select id="news-branch-id" name="branch_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm bg-white">
                        <option value="">— All Campuses / Global —</option>
                        @foreach(\App\Models\Branch::where('published', true)->get() as $b)
                            <option value="{{ $b->id }}">{{ $b->text('name') }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Story Body EN & KM -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Article Content (English) *
                    </label>
                    <textarea id="news-body-en" name="body[en]" rows="3" required placeholder="Write the details of the news story..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        ខ្លឹមសារព័ត៌មាន (Khmer)
                    </label>
                    <textarea id="news-body-km" name="body[km]" rows="3" placeholder="សរសេរខ្លឹមសារអត្ថបទជាភាសាខ្មែរ..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm"></textarea>
                </div>

                <!-- Featured Image Upload with Preview -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Featured Image / រូបភាពព័ត៌មាន
                    </label>
                    <input type="file" id="news-image" name="image" accept="image/*" onchange="previewNewsImage(event)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-ggsNavy file:text-white hover:file:bg-ggsNavyLight cursor-pointer">
                    <div id="news-image-preview-wrap" class="hidden mt-2">
                        <img id="news-image-preview" src="#" alt="Preview" class="h-28 rounded-xl object-cover border border-slate-200 shadow-sm">
                    </div>
                </div>

                <!-- Staff Verification / Authentication -->
                @auth
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>Logged in as <strong>{{ auth()->user()->name }}</strong> (Staff Member). Post will publish immediately.</span>
                    </div>
                @else
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-2.5">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                            <i class="fa-solid fa-lock text-ggsGold"></i>
                            <span>Staff Verification / ផ្ទៀងផ្ទាត់បុគ្គលិកសាលា</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-tight">Enter your staff login to verify authorization and publish directly to the live feed.</p>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <input type="email" id="news-staff-email" name="staff_email" required placeholder="Staff Email (admin@school.test)" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs">
                            </div>
                            <div>
                                <input type="password" id="news-staff-password" name="staff_password" required placeholder="Staff Password" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs">
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="pt-2">
                    <button type="submit" id="btn-submit-news" class="w-full py-3.5 bg-ggsGold hover:bg-ggsGoldHover text-ggsNavy font-bold rounded-xl shadow-md transition-colors text-sm uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចុះផ្សាយជាសាធារណៈ</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Publish News Now</span>
                    </button>
                    <div id="news-feedback" role="status" class="mt-2.5 text-center font-bold text-xs"></div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        let currentLang = '{{ app()->getLocale() }}';

        function setLanguage(lang) {
            currentLang = lang;
            const kmElements = document.querySelectorAll('.lang-km');
            const enElements = document.querySelectorAll('.lang-en');
            const btnKm = document.getElementById('btn-lang-km');
            const btnEn = document.getElementById('btn-lang-en');

            if (lang === 'km') {
                kmElements.forEach(el => el.classList.remove('hidden'));
                enElements.forEach(el => el.classList.add('hidden'));

                if(btnKm) btnKm.className = "px-3.5 py-1 rounded-full text-xs font-bold bg-ggsGold text-ggsNavy shadow-md transition-all cursor-pointer";
                if(btnEn) btnEn.className = "px-3.5 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white transition-all cursor-pointer";
                document.documentElement.lang = 'km';
            } else {
                enElements.forEach(el => el.classList.remove('hidden'));
                kmElements.forEach(el => el.classList.add('hidden'));

                if(btnEn) btnEn.className = "px-3.5 py-1 rounded-full text-xs font-bold bg-ggsGold text-ggsNavy shadow-md transition-all cursor-pointer";
                if(btnKm) btnKm.className = "px-3.5 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white transition-all cursor-pointer";
                document.documentElement.lang = 'en';
            }

            // Sync with backend session and reload to translate all server blocks
            const csrf = $('meta[name="csrf-token"]').attr('content');
            $.post('/language/' + encodeURIComponent(lang), { _token: csrf })
                .always(function() {
                    window.location.reload();
                });
        }

        // Mobile Menu Drawer Handler
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if(menu) menu.classList.toggle('hidden');
        }

        // Modal Handlers
        function openInquiryModal() {
            const modal = document.getElementById('inquiryModal');
            if(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeInquiryModal() {
            const modal = document.getElementById('inquiryModal');
            if(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // News Modal Handlers
        function openNewsModal() {
            const modal = document.getElementById('newsPostingModal');
            if(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeNewsModal() {
            const modal = document.getElementById('newsPostingModal');
            if(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function previewNewsImage(event) {
            const input = event.target;
            const previewWrap = document.getElementById('news-image-preview-wrap');
            const preview = document.getElementById('news-image-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewWrap.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewWrap.classList.add('hidden');
            }
        }

        function handleNewsSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('news-posting-form');
            const formData = new FormData(form);
            const btn = document.getElementById('btn-submit-news');
            const feedback = document.getElementById('news-feedback');

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Publishing...';
            feedback.textContent = '';

            $.ajax({
                url: '{{ route('news.post') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    toastr.success(res.message || 'News published successfully!');
                    closeNewsModal();
                    form.reset();
                    if (res.redirect) {
                        setTimeout(() => window.location.href = res.redirect, 900);
                    } else {
                        setTimeout(() => window.location.reload(), 900);
                    }
                },
                error: function(xhr) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-paper-plane text-xs"></i> Publish News Now';
                    let errorMsg = xhr.responseJSON?.message || 'Failed to post news. Please check inputs.';
                    if (xhr.responseJSON?.errors) {
                        const errs = Object.values(xhr.responseJSON.errors).flat();
                        errorMsg = errs.join('<br>');
                    }
                    feedback.className = 'mt-2.5 text-center font-bold text-xs text-rose-600';
                    feedback.innerHTML = errorMsg;
                    toastr.error(errorMsg);
                }
            });
        }

        function handleModalSubmit(e) {
            e.preventDefault();
            const name = $('#inquiry-name').val();
            const email = $('#inquiry-email').val();
            const phone = $('#inquiry-phone').val();
            const note = $('#inquiry-message').val();
            const message = "Campus Tour Booking Inquiry. Phone: " + phone + ". Note: " + (note || "N/A");

            const csrf = $('meta[name="csrf-token"]').attr('content');
            $.post('{{ route('contact') }}', {
                _token: csrf,
                name: name,
                email: email,
                phone: phone,
                message: message
            }).done(function(r) {
                toastr.success(r.message || "Thank you! Your campus tour inquiry has been submitted.");
                closeInquiryModal();
                $('#inquiry-form')[0].reset();
            }).fail(function(x) {
                let msg = x.responseJSON?.message || 'Please verify your information and try again.';
                toastr.error(msg);
            });
        }

        // Contact Form AJAX Handler
        $(function() {
            $(document).on('submit', '#contact-form', function(e) {
                e.preventDefault();
                const form = $(this);
                const button = form.find('button[type=submit]');
                button.prop('disabled', true);
                
                $.post(form.attr('action'), form.serialize())
                    .done(function(r) {
                        form[0].reset();
                        $('#contact-feedback').removeClass('text-rose-600').addClass('text-emerald-600 font-bold text-base').text(r.message);
                        toastr.success(r.message);
                    })
                    .fail(function(x) {
                        let msg = x.responseJSON?.message || 'Please check your inputs and try again.';
                        $('#contact-feedback').removeClass('text-emerald-600').addClass('text-rose-600 font-bold text-base').text(msg);
                        toastr.error(msg);
                    })
                    .always(() => button.prop('disabled', false));
            });
        });
    </script>
    @include('site.partials.chat-widget')
</body>
</html>
