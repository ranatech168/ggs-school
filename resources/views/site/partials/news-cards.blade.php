<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
    @forelse($posts as $post)
        @php
            $imgUrl = method_exists($post, 'imageUrl') ? $post->imageUrl() : ($post->image ? (str_starts_with($post->image, 'school/') ? asset('storage/'.$post->image) : asset($post->image)) : null);
            $categoryName = $post->category ?? 'Campus News';
            $dateFormatted = $post->published_at ? $post->published_at->format('d M Y') : ($post->created_at ? $post->created_at->format('d M Y') : date('d M Y'));
            $authorName = $post->author ?? 'GGS Editorial Desk';
            $readTime = $post->read_time ?? '3 min read';
            $summaryText = method_exists($post, 'text') && $post->text('summary') ? $post->text('summary') : (method_exists($post, 'text') ? \Illuminate\Support\Str::limit(strip_tags($post->text('body')), 130) : '');
        @endphp
        <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
            <!-- Article Image with Badges -->
            <div class="relative overflow-hidden aspect-[16/10] bg-slate-900">
                @if($imgUrl)
                    <img src="{{ $imgUrl }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                         alt="{{ $post->text('title') }}"
                         loading="lazy">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-ggsNavy to-slate-800 text-white p-6 text-center">
                        <i class="fa-solid fa-newspaper text-3xl text-ggsGold mb-2 opacity-80"></i>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-300">GGS Official News</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent opacity-60"></div>
                
                <!-- Category Badge -->
                <div class="absolute top-3 left-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-ggsNavy/90 backdrop-blur-md text-ggsGold border border-ggsGold/30 shadow-sm">
                        {{ $categoryName }}
                    </span>
                </div>

                <!-- Read Time Badge -->
                <div class="absolute top-3 right-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-black/60 backdrop-blur-md text-white border border-white/10">
                        <i class="fa-regular fa-clock text-[10px] text-ggsGold"></i>
                        <span>{{ $readTime }}</span>
                    </span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 sm:p-7 flex flex-col flex-1">
                <!-- Metadata: Date & Author -->
                <div class="flex items-center justify-between text-xs text-slate-500 font-semibold mb-3">
                    <div class="inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar text-ggsGold"></i>
                        <span>{{ $dateFormatted }}</span>
                    </div>
                    <div class="inline-flex items-center gap-1.5 text-slate-400">
                        <i class="fa-solid fa-feather text-[10px]"></i>
                        <span class="truncate max-w-[120px]">{{ $authorName }}</span>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-lg sm:text-xl font-bold font-battambang text-ggsNavy group-hover:text-ggsGold transition-colors leading-snug mb-3 line-clamp-2">
                    <a href="{{ route('article', $post->slug) }}">
                        {{ $post->text('title') }}
                    </a>
                </h3>

                <!-- Summary Excerpt -->
                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-6 flex-1 line-clamp-3">
                    {{ $summaryText }}
                </p>

                <!-- Footer Card Action -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                    <a class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-ggsNavy group-hover:text-ggsGold transition-colors" 
                       href="{{ route('article', $post->slug) }}">
                        <span>{{ __('site.read') }}</span>
                        <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <span class="text-[11px] font-semibold text-slate-400">
                        <i class="fa-solid fa-shield-halved text-ggsGold/80 me-1"></i> Verified
                    </span>
                </div>
            </div>
        </article>
    @empty
        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300 p-8 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-2xl mb-4">
                <i class="fa-regular fa-newspaper"></i>
            </div>
            <h4 class="text-base sm:text-lg font-bold font-battambang text-ggsNavy mb-1">
                {{ __('site.empty') }}
            </h4>
            <p class="text-slate-500 text-xs sm:text-sm max-w-md mx-auto">
                No articles were found matching your criteria. Check back soon or explore other categories.
            </p>
        </div>
    @endforelse
</div>
