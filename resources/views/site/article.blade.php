@extends('site.layout')

@section('title', $post->text('title') . ' | Global Generation School')

@section('content')
@php
    $imgUrl = method_exists($post, 'imageUrl') ? $post->imageUrl() : ($post->image ? (str_starts_with($post->image, 'school/') ? asset('storage/'.$post->image) : asset($post->image)) : null);
    $categoryName = $post->category ?? 'Campus News';
    $dateFormatted = $post->published_at ? $post->published_at->format('d M Y') : ($post->created_at ? $post->created_at->format('d M Y') : date('d M Y'));
    $authorName = $post->author ?? 'GGS Editorial Desk';
    $readTime = $post->read_time ?? '3 min read';
@endphp

<article class="py-12 sm:py-20 bg-slate-50 min-h-[85vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs & Back Navigation -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <a class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-ggsNavy hover:text-ggsGold transition-colors group px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-sm" 
               href="{{ route('news') }}">
                <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform text-ggsGold"></i>
                <span>{{ __('site.back') }}</span>
            </a>

            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('home') }}" class="hover:text-ggsNavy">Home</a>
                <span>/</span>
                <a href="{{ route('news') }}" class="hover:text-ggsNavy">News</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold truncate max-w-[180px]">{{ $categoryName }}</span>
            </div>
        </div>

        <!-- Article Card Shell -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 lg:p-14 border border-slate-200/90 shadow-lg">
            
            <!-- Metadata Row -->
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase bg-amber-100 text-amber-900 border border-amber-300 shadow-sm">
                    <i class="fa-solid fa-tag text-[10px] me-1.5 text-amber-700"></i>
                    {{ $categoryName }}
                </span>

                <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-semibold">
                    <i class="fa-regular fa-calendar text-ggsGold"></i>
                    <span>{{ $dateFormatted }}</span>
                </span>

                <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                    <i class="fa-regular fa-clock text-ggsGold"></i>
                    <span>{{ $readTime }}</span>
                </span>

                <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium ms-auto">
                    <i class="fa-solid fa-feather text-ggsGold text-[11px]"></i>
                    <span>{{ $authorName }}</span>
                </span>
            </div>

            <!-- Article Title -->
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold font-battambang text-ggsNavy leading-tight tracking-tight mb-8">
                {{ $post->text('title') }}
            </h1>

            <!-- Featured Image -->
            @if($imgUrl)
                <div class="rounded-2xl overflow-hidden shadow-md border border-slate-200/80 mb-10 bg-slate-900 aspect-[16/9] relative group">
                    <img class="w-full h-full object-cover" 
                         src="{{ $imgUrl }}" 
                         alt="{{ $post->text('title') }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60"></div>
                </div>
            @endif

            <!-- Summary Callout (if available) -->
            @if(method_exists($post, 'text') && $post->text('summary'))
                <div class="mb-8 p-5 sm:p-6 bg-slate-50 border-l-4 border-ggsGold rounded-r-2xl text-slate-700 text-base sm:text-lg italic font-medium leading-relaxed">
                    {{ $post->text('summary') }}
                </div>
            @endif

            <!-- Article Body -->
            <div class="prose prose-base sm:prose-lg max-w-none text-slate-700 leading-relaxed font-sans space-y-4">
                {!! nl2br(e($post->text('body'))) !!}
            </div>

            <!-- Author Byline & Official Sign-off -->
            <div class="mt-12 pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-ggsNavy text-ggsGold flex items-center justify-center text-lg font-bold shadow-md">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-ggsNavy font-battambang">
                            Global Generation School (GGS)
                        </h4>
                        <p class="text-xs text-slate-500">
                            Siem Reap Campus • Official Academic Media Desk
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('news') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-ggsNavy text-xs font-bold transition-all">
                        Browse More News
                    </a>
                </div>
            </div>

        </div>

        <!-- RELATED STORIES SECTION -->
        @if(isset($related) && $related->isNotEmpty())
            <div class="mt-16">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-ggsGold block mb-1">More Stories</span>
                        <h3 class="text-2xl font-bold font-battambang text-ggsNavy">Related Publications</h3>
                    </div>
                    <a href="{{ route('news') }}" class="text-xs sm:text-sm font-bold text-ggsNavy hover:text-ggsGold transition-colors">
                        View All →
                    </a>
                </div>
                @include('site.partials.news-cards', ['posts' => $related])
            </div>
        @endif

    </div>
</article>
@endsection
