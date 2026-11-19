@extends('site.layout')

@section('content')
<section class="py-16 sm:py-24 bg-slate-50 min-h-[75vh]">
    <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
        
        <!-- HEADER -->
        <div class="mb-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase bg-amber-100 text-amber-900 border border-amber-200/80 mb-3">
                <i class="fa-solid fa-school-flag text-ggsGold text-xs"></i>
                {{ __('site.branches') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold font-battambang text-ggsNavy leading-tight tracking-tight">
                {{ $branch->text('name') }}
            </h1>
        </div>

        <!-- BRANCH OVERVIEW -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-16">
            <div class="lg:col-span-7 bg-white rounded-2xl p-6 sm:p-9 border border-slate-200/80 shadow-sm space-y-6">
                <p class="text-base sm:text-lg text-slate-700 leading-relaxed whitespace-pre-line font-sans">
                    {{ $branch->text('description') }}
                </p>

                <div class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-slate-700 text-xs sm:text-sm">
                    @if($branch->text('address'))
                        <div class="sm:col-span-2 flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-ggsGold flex items-center justify-center shrink-0 mt-0.5 text-base">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('site.address') ?? 'Address' }}</span>
                                <span class="font-medium text-slate-800">{{ $branch->text('address') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($branch->phone)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-ggsGold flex items-center justify-center shrink-0 text-base">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('site.phone') ?? 'Phone' }}</span>
                                <a href="tel:{{ $branch->phone }}" class="font-semibold text-ggsNavy hover:text-ggsGold transition-colors">{{ $branch->phone }}</a>
                            </div>
                        </div>
                    @endif

                    @if($branch->email)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-ggsGold flex items-center justify-center shrink-0 text-base">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('site.email') ?? 'Email' }}</span>
                                <a href="https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox?compose=new" target="_blank" rel="noopener noreferrer" class="font-semibold text-ggsNavy hover:text-ggsGold transition-colors" title="Send email to {{ $branch->email }} via Gmail">{{ $branch->email }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($branch->image)
                <div class="lg:col-span-5 rounded-2xl overflow-hidden shadow-md border border-slate-200/80 bg-slate-100 aspect-[4/3] lg:aspect-auto lg:h-full">
                    <img class="w-full h-full object-cover min-h-[320px]" src="{{ str_starts_with($branch->image,'school/') ? asset('storage/'.$branch->image) : asset($branch->image) }}" alt="{{ $branch->text('name') }}">
                </div>
            @endif
        </div>

        <!-- BRANCH PROGRAMS -->
        <div class="mb-16">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-1.5 h-7 bg-ggsGold rounded-full"></div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-battambang text-ggsNavy tracking-tight">
                    {{ __('site.programs') }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($programs as $item)
                    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-ggsNavy text-ggsGold flex items-center justify-center text-xl mb-4 shadow-sm">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold font-battambang text-ggsNavy mb-2">
                            {{ $item->text('title') }}
                        </h3>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            {{ $item->text('body') }}
                        </p>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center bg-white rounded-2xl border border-dashed border-slate-300 p-6">
                        <p class="text-slate-500 font-medium text-sm">{{ __('site.empty') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- BRANCH NEWS -->
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="w-1.5 h-7 bg-ggsGold rounded-full"></div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-battambang text-ggsNavy tracking-tight">
                    {{ __('site.news') }}
                </h2>
            </div>

            @include('site.partials.news-cards')

            <div class="mt-12 flex justify-center">
                {{ $posts->links() }}
            </div>
        </div>

    </div>
</section>
@endsection
