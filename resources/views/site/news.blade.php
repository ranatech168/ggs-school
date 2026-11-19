@extends('site.layout')

@section('title', __('site.news') . ' | Global Generation School')

@section('content')
<!-- NEWS HERO HEADER -->
@php
    $headerBlock = $newsBlocks['news_header'] ?? null;
    $spotlightBlock = $newsBlocks['news_spotlight'] ?? null;
    $announcementBlock = $newsBlocks['news_announcement'] ?? null;
@endphp

<section class="relative bg-gradient-to-b from-[#07162B] via-[#0B2240] to-[#122B4D] text-white pt-20 pb-28 overflow-hidden border-b border-white/10">
    <!-- Background Architectural Glow & Pattern -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#F5A623_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-ggsGold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase bg-white/10 text-ggsGold border border-ggsGold/30 backdrop-blur-md mb-5 shadow-sm">
                <i class="fa-solid fa-newspaper text-ggsGold"></i>
                <span>{{ $headerBlock ? $headerBlock->text('subtitle') : (app()->getLocale() === 'km' ? 'ព័ត៌មាន & សកម្មភាព' : 'Official Newsroom & Media') }}</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold font-battambang text-white leading-tight tracking-tight mb-5 drop-shadow-sm">
                {{ $headerBlock ? $headerBlock->text('title') : (app()->getLocale() === 'km' ? 'ព្រឹត្តិការណ៍ថ្មីៗ និងការចុះផ្សាយព័ត៌មានសិក្សា' : 'School News, Stories & Academic Updates') }}
            </h1>

            <!-- Description -->
            <p class="text-slate-300 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mx-auto mb-8 font-normal">
                {{ $headerBlock ? $headerBlock->text('body') : (app()->getLocale() === 'km' ? 'តាមដានព័ត៌មាន សមិទ្ធផល សកម្មភាពសិក្សា និងសេចក្តីប្រកាសសំខាន់ៗរបស់សាលាជំនាន់សកល។' : 'Discover recent breakthroughs, student showcases, campus life, and institutional announcements from Global Generation School.') }}
            </p>

            <!-- Search Form -->
            <form action="{{ route('news') }}" method="GET" class="max-w-xl mx-auto flex items-center bg-white/10 backdrop-blur-md rounded-2xl p-1.5 border border-white/20 shadow-xl focus-within:border-ggsGold/80 focus-within:bg-white/15 transition-all">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="pl-4 pr-2 text-slate-300">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </div>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="{{ app()->getLocale() === 'km' ? 'ស្វែងរកព័ត៌មាន ឬប្រធានបទ...' : 'Search news, stories, or announcements...' }}" 
                       class="w-full bg-transparent border-0 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-0 py-2">
                @if(request('q'))
                    <a href="{{ route('news', array_filter(['category' => request('category')])) }}" class="px-2 text-slate-400 hover:text-white text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-ggsGold hover:bg-amber-400 text-ggsNavy font-bold text-xs sm:text-sm transition-all shadow-md">
                    {{ app()->getLocale() === 'km' ? 'ស្វែងរក' : 'Search' }}
                </button>
            </form>
        </div>
    </div>
</section>

<!-- MAIN CONTENT WRAPPER -->
<section class="py-12 sm:py-20 bg-slate-50 min-h-[75vh]">
    <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">

        <!-- CAMPUS BULLETIN / ANNOUNCEMENT BLOCK (if published) -->
        @if($announcementBlock && $announcementBlock->published)
            <div class="mb-12 bg-gradient-to-r from-amber-500/10 via-amber-50 to-white border-l-4 border-ggsGold rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-ggsGold/20 text-ggsNavy flex items-center justify-center text-lg font-bold shrink-0">
                        <i class="{{ $announcementBlock->icon ?: 'fa-solid fa-bullhorn' }} text-amber-700"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-300">
                                {{ $announcementBlock->text('subtitle') ?: 'Official Bulletin' }}
                            </span>
                            <span class="text-xs text-slate-500">• Announcement</span>
                        </div>
                        <h4 class="font-battambang font-bold text-base sm:text-lg text-ggsNavy leading-snug">
                            {{ $announcementBlock->text('title') }}
                        </h4>
                        @if($announcementBlock->text('body'))
                            <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">
                                {{ $announcementBlock->text('body') }}
                            </p>
                        @endif
                    </div>
                </div>
                @if($announcementBlock->link_url)
                    <a href="{{ $announcementBlock->link_url }}" class="self-end sm:self-center px-4 py-2 bg-ggsNavy hover:bg-slate-800 text-ggsGold font-bold text-xs rounded-xl shadow-sm transition-all whitespace-nowrap">
                        <span>{{ $announcementBlock->text('link_text') ?: 'Learn More' }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px] ms-1.5"></i>
                    </a>
                @endif
            </div>
        @endif

        <!-- FEATURED PUBLISHING SPOTLIGHT BLOCK -->
        @if($featured && empty(request('q')) && (empty(request('category')) || request('category') === 'all'))
            @php
                $featImg = method_exists($featured, 'imageUrl') ? $featured->imageUrl() : ($featured->image ? (str_starts_with($featured->image, 'school/') ? asset('storage/'.$featured->image) : asset($featured->image)) : null);
                $featCategory = $featured->category ?? 'Featured Spotlight';
                $featDate = $featured->published_at ? $featured->published_at->format('d M Y') : ($featured->created_at ? $featured->created_at->format('d M Y') : date('d M Y'));
                $featAuthor = $featured->author ?? 'GGS Editorial Desk';
                $featRead = $featured->read_time ?? '4 min read';
                $featSummary = method_exists($featured, 'text') && $featured->text('summary') ? $featured->text('summary') : (method_exists($featured, 'text') ? \Illuminate\Support\Str::limit(strip_tags($featured->text('body')), 220) : '');
            @endphp
            <div class="mb-16">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-ggsGold animate-pulse"></span>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-ggsNavy">
                        {{ app()->getLocale() === 'km' ? 'ការចុះផ្សាយព័ត៌មានពិសេស' : 'Featured Publishing Spotlight' }}
                    </span>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-lg hover:shadow-2xl transition-all duration-300 grid lg:grid-cols-12 group">
                    <!-- Left: Large Cover Image with Badges -->
                    <div class="relative lg:col-span-7 aspect-[16/10] lg:aspect-auto lg:min-h-[420px] bg-slate-900 overflow-hidden">
                        @if($featImg)
                            <img src="{{ $featImg }}" 
                                 alt="{{ $featured->text('title') }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#0B2240] to-slate-800 text-white p-8 text-center">
                                <i class="fa-solid fa-newspaper text-5xl text-ggsGold mb-3"></i>
                                <span class="text-sm font-bold uppercase tracking-wider text-slate-300">GGS Spotlight Publishing</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                        <!-- Top Badges -->
                        <div class="absolute top-5 left-5 flex flex-wrap gap-2">
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase bg-ggsGold text-ggsNavy shadow-md">
                                <i class="fa-solid fa-star text-[10px] me-1"></i> Spotlight
                            </span>
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase bg-ggsNavy/90 backdrop-blur-md text-white border border-white/20 shadow-md">
                                {{ $featCategory }}
                            </span>
                        </div>

                        <!-- Bottom Overlaid Meta for Mobile -->
                        <div class="absolute bottom-4 left-5 right-5 flex items-center justify-between text-xs text-white/90 font-medium lg:hidden">
                            <span><i class="fa-regular fa-calendar text-ggsGold me-1.5"></i>{{ $featDate }}</span>
                            <span><i class="fa-regular fa-clock text-ggsGold me-1.5"></i>{{ $featRead }}</span>
                        </div>
                    </div>

                    <!-- Right: Story Details & Actions -->
                    <div class="lg:col-span-5 p-7 sm:p-10 lg:p-12 flex flex-col justify-between bg-gradient-to-b from-white to-slate-50/50">
                        <div>
                            <!-- Header Meta -->
                            <div class="hidden lg:flex items-center gap-4 text-xs font-semibold text-slate-500 mb-4">
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar text-ggsGold"></i>
                                    {{ $featDate }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-regular fa-clock text-ggsGold"></i>
                                    {{ $featRead }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-slate-400">
                                    <i class="fa-solid fa-feather text-[10px]"></i>
                                    {{ $featAuthor }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="text-2xl sm:text-3xl font-extrabold font-battambang text-ggsNavy group-hover:text-ggsGold transition-colors leading-tight mb-4">
                                <a href="{{ route('article', $featured->slug) }}">
                                    {{ $featured->text('title') }}
                                </a>
                            </h2>

                            <!-- Excerpt -->
                            <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6 font-normal">
                                {{ $featSummary }}
                            </p>
                        </div>

                        <!-- CTA & Sign-off -->
                        <div class="pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
                            <a href="{{ route('article', $featured->slug) }}" 
                               class="inline-flex items-center gap-2.5 px-6 py-3 rounded-xl bg-ggsNavy hover:bg-slate-800 text-ggsGold font-bold text-xs sm:text-sm transition-all shadow-md group/btn">
                                <span>{{ app()->getLocale() === 'km' ? 'អានការចុះផ្សាយលម្អិត' : 'Read Spotlight Story' }}</span>
                                <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1.5 transition-transform"></i>
                            </a>
                            <span class="text-xs text-slate-400 font-medium">
                                <i class="fa-solid fa-certificate text-ggsGold me-1"></i> Editorial Feature
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- CATEGORY FILTERS & HEADER BAR -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 pb-6 border-b border-slate-200">
            <div>
                <h3 class="text-xl sm:text-2xl font-extrabold font-battambang text-ggsNavy">
                    @if(request('q'))
                        <span>Search Results for "<span class="text-ggsGold">{{ request('q') }}</span>"</span>
                    @elseif(request('category') && request('category') !== 'all')
                        <span>{{ request('category') }} Publications</span>
                    @else
                        <span>{{ app()->getLocale() === 'km' ? 'អត្ថបទ និងការផ្សាយថ្មីៗទាំងអស់' : 'All Recent Publications & Articles' }}</span>
                    @endif
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Showing {{ $posts->total() }} total {{ \Illuminate\Support\Str::plural('article', $posts->total()) }}
                </p>
            </div>

            <!-- Category Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('news', array_filter(['q' => request('q')])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty(request('category')) || request('category') === 'all' ? 'bg-ggsNavy text-ggsGold shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    All
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('news', array_filter(['category' => $cat, 'q' => request('q')])) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('category') === $cat ? 'bg-ggsNavy text-ggsGold shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- NEWS ARTICLES GRID -->
        <div>
            @include('site.partials.news-cards', ['posts' => $posts])
        </div>

        <!-- PAGINATION -->
        @if($posts->hasPages())
            <div class="mt-14 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif

    </div>
</section>
@endsection
