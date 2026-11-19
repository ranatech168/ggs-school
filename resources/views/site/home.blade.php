@extends('site.layout')

@section('content')
    @php
        $heroBlock = isset($blocks['hero']) ? $blocks['hero']->first() : null;
        $metricBlocks = $blocks['metrics'] ?? collect();
        $whyShift = isset($blocks['why_ggs']) ? $blocks['why_ggs']->firstWhere('key', 'why_ggs_shift') : null;
        $whyCards = isset($blocks['why_ggs']) ? $blocks['why_ggs']->where('key', '!=', 'why_ggs_shift') : collect();
        $pillarBlocks = $blocks['pillars'] ?? collect();
        $alpBlock = isset($blocks['accelerated']) ? $blocks['accelerated']->first() : null;
        $serviceMain = isset($blocks['service_learning']) ? $blocks['service_learning']->firstWhere('key', 'service_main') : null;
        $serviceShowcases = isset($blocks['service_learning']) ? $blocks['service_learning']->where('key', '!=', 'service_main') : collect();
        $actionClubs = $blocks['action_clubs'] ?? collect();
        $cpBlock = isset($blocks['child_protection']) ? $blocks['child_protection']->first() : null;
        $newsBlock = isset($blocks['news']) ? $blocks['news']->firstWhere('key', 'news_header') : (isset($blocks['news']) ? $blocks['news']->first() : null);

        $heroImg = $heroBlock?->image ?: ($hero?->image ? (str_starts_with($hero->image,'school/') ? asset('storage/'.$hero->image) : asset($hero->image)) : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2000&auto=format&fit=crop');
        if ($heroImg && str_starts_with($heroImg, 'school/')) {
            $heroImg = asset('storage/' . $heroImg);
        }
    @endphp

    <!-- HERO SECTION -->
    <section id="home" class="relative min-h-[85vh] flex items-center bg-cover bg-center bg-no-repeat text-white overflow-hidden" 
             style="background-image: url('{{ $heroImg }}');">
        <div class="absolute inset-0 hero-overlay"></div>
        
        <div class="relative max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12 py-20 sm:py-28 lg:py-32 z-10 w-full">
            <div class="max-w-4xl space-y-6">
                
                <!-- Motto Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/15 backdrop-blur-md border border-ggsGold/60 text-ggsGold font-bold text-xs sm:text-sm shadow-lg">
                    <i class="{{ $heroBlock?->icon ?: 'fa-solid fa-heart-pulse' }} text-rose-400 text-sm"></i>
                    <span>
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('subtitle', 'km') ?: 'បេះដូងស្នេហាជាតិ គំនិតកម្រិតសកល' }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('subtitle', 'en') ?: 'Patriotic Heart, Global Mind' }}</span>
                    </span>
                </div>

                <!-- Headline -->
                <h4 class="font-battambang text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-extrabold leading-[1.75] sm:leading-[1.8] text-white khmer-title-glow tracking-[4px] text-center mx-auto hero-title-shadow" style="letter-spacing: 4px;">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('title', 'km') ?: ($hero?->textFor('title', 'km') ?: 'ការអប់រំប្រកបដោយគុណភាព និងការផ្លាស់ប្តូរឌីជីថល (អនាគតភ្លឺស្វាងចាប់ផ្តើមពីទីនេះ)') }}</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('title', 'en') ?: ($hero?->textFor('title', 'en') ?: 'A brighter future starts here: Transformative 21st Century Bilingual & Digital Education') }}</span>
                </h4>

                <!-- Subheadline -->
                <p class="text-base sm:text-lg lg:text-xl text-slate-100 leading-relaxed font-medium max-w-3xl text-center mx-auto hero-body-shadow">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('body', 'km') ?: ($hero?->textFor('body', 'km') ?: 'សាលាជំនាន់សកល (GGS) ក្រុងសៀមរាប ផ្តល់ជូននូវបទពិសោធន៍អប់រំឈានមុខគេ តាមរយៈការរួមបញ្ចូលគ្នារវាងកម្មវិធីសិក្សាជាតិ ភាសាអង់គ្លេស STEM បច្ចេកវិទ្យា AI និរន្តរភាពបរិស្ថាន និងបំណិនជីវិតពិតប្រាកដ។') }}</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('body', 'en') ?: ($hero?->textFor('body', 'en') ?: 'Global Generation School (GGS) in Siem Reap equips Cambodian youth with rigorous academic excellence, fluent English, STEM innovation, AI literacy, life skills, and environmental sustainability.') }}</span>
                </p>

                <!-- Call to Action -->
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <button onclick="openInquiryModal()" class="px-7 py-3.5 sm:px-8 sm:py-4 bg-ggsGold hover:bg-ggsGoldHover text-ggsNavy font-bold rounded-xl shadow-xl transition-all transform hover:-translate-y-0.5 text-center text-sm sm:text-base uppercase tracking-wider flex items-center justify-center gap-2.5 cursor-pointer">
                        <i class="fa-solid fa-pen-to-square text-base"></i>
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $heroBlock?->extra['cta_text']['km'] ?? 'ចុះឈ្មោះចូលរៀន ២០២៦-២០២៧' }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $heroBlock?->extra['cta_text']['en'] ?? 'Admissions 2026-2027' }}</span>
                    </button>
                    
                    <a href="{{ $heroBlock?->link_url ?: '#pillars' }}" class="px-7 py-3.5 sm:px-8 sm:py-4 bg-white/20 hover:bg-white/30 text-white font-bold rounded-xl backdrop-blur-md border border-white/40 transition-all text-center text-sm sm:text-base flex items-center justify-center gap-2.5 shadow-md">
                        <span>
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('link_text', 'km') ?: 'ស្វែងយល់សសរស្តម្ភទាំង៦' }}</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $heroBlock?->textFor('link_text', 'en') ?: 'Explore 6 Pillars' }}</span>
                        </span>
                        <i class="fa-solid fa-arrow-down text-ggsGold text-sm"></i>
                    </a>
                </div>

                <!-- Quick Highlights -->
                <div class="pt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-white/20 text-xs sm:text-sm text-slate-100 font-medium">
                    @if($heroBlock && !empty($heroBlock->extra['highlights']))
                        @foreach($heroBlock->extra['highlights'] as $hl)
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-circle-check text-ggsGold text-base"></i>
                                <span>
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $hl['km'] ?? $hl['en'] ?? '' }}</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $hl['en'] ?? $hl['km'] ?? '' }}</span>
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-ggsGold text-base"></i>
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទទួលស្គាល់ដោយ ក្រសួងអប់រំ</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">MoEYS Recognized</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-ggsGold text-base"></i>
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">រៀន STEM & AI ជាក់ស្តែង</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Hands-on STEM & AI</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-ggsGold text-base"></i>
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សុវត្ថិភាពកុមារ១០០%</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">100% Child Safety</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- 4-METRIC SUMMARY BAR -->
    <section class="relative z-20 -mt-10 max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/90 p-6 sm:p-8 grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 text-center">
            @forelse($metricBlocks as $mb)
                <div class="p-4 sm:p-6 rounded-xl bg-slate-50 border border-slate-100 hover:border-ggsGold/50 transition-all">
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black {{ $loop->index % 2 === 0 ? 'text-ggsNavy' : 'text-ggsGold' }} tracking-tight mb-2">
                        {{ $mb->extra['value'] ?? '100%' }}
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-slate-700 leading-snug">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $mb->textFor('title', 'km') }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $mb->textFor('title', 'en') }}</span>
                    </div>
                </div>
            @empty
                <div class="p-4 sm:p-6 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-ggsNavy tracking-tight mb-2">6</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-700">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សសរស្តម្ភយុទ្ធសាស្ត្រ</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Strategic Pillars</span>
                    </div>
                </div>
                <div class="p-4 sm:p-6 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-ggsGold tracking-tight mb-2">10</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-700">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">បំណិនជីវិតគោល</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Core Life Skills</span>
                    </div>
                </div>
                <div class="p-4 sm:p-6 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-ggsNavy tracking-tight mb-2">100%</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-700">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ការយកចិត្តទុកដាក់លើកុមារ</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Child-Centered Focus</span>
                    </div>
                </div>
                <div class="p-4 sm:p-6 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-ggsGold tracking-tight mb-2">STEM</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-700">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">នវានុវត្តន៍ & Hydroponics</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Innovation & Hydroponics</span>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- WHY GGS SECTION -->
    <section id="why-ggs" class="py-20 sm:py-28 bg-white">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-block text-ggsNavy font-bold text-xs sm:text-sm uppercase tracking-widest bg-ggsNavy/10 px-5 py-2 rounded-full border border-ggsNavy/15">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទស្សនវិស័យអប់រំ GGS</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Educational Vision</span>
                </span>
                <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy leading-tight">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">តើការអប់រំរបស់ សាលាជំនាន់សកល GGS គឺជាអ្វី?</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">What is Global Generation School (GGS) Education?</span>
                </h2>
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                    @if($about)
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $about->body['km'] ?? ($about->text('body') ?: 'សាលាជំនាន់សកល (GGS) ផ្តល់ជូននូវបទពិសោធន៍អប់រំដែលមានគុណភាព និងការផ្លាស់ប្តូរ ដែលត្រូវបានរៀបចំសិស្សសម្រាប់ភាពចម្រុះក្នុងសតវត្សរ៍ទី ២១ នេះ។') }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $about->body['en'] ?? ($about->text('body') ?: 'GGS delivers a high-quality, transformative educational experience designed to empower Cambodian students to navigate and lead in a rapidly evolving global society.') }}</span>
                    @else
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សាលាជំនាន់សកល (GGS) ផ្តល់ជូននូវបទពិសោធន៍អប់រំដែលមានគុណភាព និងការផ្លាស់ប្តូរ ដែលត្រូវបានរៀបចំសិស្សសម្រាប់ភាពចម្រុះក្នុងសតវត្សរ៍ទី ២១ នេះ។</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">GGS delivers a high-quality, transformative educational experience designed to empower Cambodian students to navigate and lead in a rapidly evolving global society.</span>
                    @endif
                </p>
            </div>

            <!-- Transformation Highlight Box -->
            @if($whyShift)
                <div class="bg-slate-900 text-white rounded-3xl p-8 sm:p-10 lg:p-12 shadow-xl mb-16 relative overflow-hidden border border-slate-800">
                    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-ggsGold/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 grid lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-6 space-y-4">
                            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-lg bg-ggsGold/20 text-ggsGold text-xs sm:text-sm font-bold uppercase tracking-wider">
                                <i class="{{ $whyShift->icon ?: 'fa-solid fa-arrows-spin' }} text-sm"></i>
                                <span>
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $whyShift->textFor('subtitle', 'km') }}</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $whyShift->textFor('subtitle', 'en') }}</span>
                                </span>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-extrabold font-battambang leading-snug text-white">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $whyShift->textFor('title', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $whyShift->textFor('title', 'en') }}</span>
                            </h3>
                            <p class="text-slate-200 text-sm sm:text-base leading-relaxed">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $whyShift->textFor('body', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $whyShift->textFor('body', 'en') }}</span>
                            </p>
                        </div>

                        <div class="lg:col-span-6 grid sm:grid-cols-2 gap-4 sm:gap-5">
                            <div class="bg-white/5 border border-white/15 p-6 rounded-2xl">
                                <div class="text-xs sm:text-sm font-bold text-slate-300 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-minus text-rose-400 text-sm"></i>
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ការចូលរួមសាមញ្ញ</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Simple Compliance</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សិស្សធ្វើតាមការណែនាំ បញ្ចប់កិច្ចការ ព្រោះពួកគេចង់បានពិន្ទុល្អ ឬធ្វើតាមការតម្រូវរបស់គ្រូ។</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Students follow instructions and complete assignments primarily for grades or basic compliance.</span>
                                </p>
                            </div>

                            <div class="bg-ggsNavy border-2 border-ggsGold/60 p-6 rounded-2xl shadow-lg">
                                <div class="text-xs sm:text-sm font-bold text-ggsGold uppercase tracking-wider mb-2.5 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-ggsGold text-sm"></i>
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ការផ្តល់អំណាចពិតប្រាកដ</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Authentic Empowerment</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-100 leading-relaxed">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សិស្សបង្កើតសំណួរស្រាវជ្រាវ ជ្រើសរើសវិធីសាស្ត្ររៀន និងវិនិយោគលើគោលបំណងផ្ទាល់ខ្លួន។</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Students define inquiries, choose discovery methods, and invest in their personal purpose.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Whole Child Pillars -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($whyCards as $wc)
                    <div class="bg-slate-50 p-6 sm:p-7 rounded-2xl border border-slate-200/90 hover:border-ggsGold hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-xl bg-ggsNavy text-ggsGold flex items-center justify-center text-xl font-bold mb-4 shadow-sm">
                                <i class="{{ $wc->icon ?: 'fa-solid fa-star' }} text-ggsGold"></i>
                            </div>
                            <h4 class="font-bold text-lg sm:text-xl text-ggsNavy mb-2.5 leading-snug font-battambang">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $wc->textFor('title', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $wc->textFor('title', 'en') }}</span>
                            </h4>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $wc->textFor('body', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $wc->textFor('body', 'en') }}</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 6 STRATEGIC PILLARS SECTION -->
    <section id="pillars" class="py-20 sm:py-28 bg-slate-100/80 border-y border-slate-200">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-block text-ggsNavy font-bold text-xs sm:text-sm uppercase tracking-widest bg-white px-5 py-2 rounded-full border border-slate-200 shadow-sm">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ក្របខ័ណ្ឌយុទ្ធសាស្ត្រ ២០២៦-២០៣០</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Strategic Framework 2026-2030</span>
                </span>
                <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy leading-tight">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សសរស្តម្ភយុទ្ធសាស្ត្រទាំង ៦ របស់ GGS</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">The 6 Strategic Pillars of GGS</span>
                </h2>
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សសរស្តម្ភទាំងនេះធានាថា សិស្សទទួលបានចំណេះដឹង បំណិន និងឥរិយាបថរឹងមាំសម្រាប់អនាគត។</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Core foundational pillars driving our holistic educational framework for global readiness.</span>
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
                @foreach($pillarBlocks as $pb)
                    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 text-ggsNavy flex items-center justify-center text-xl font-bold mb-4 shadow-sm">
                                <i class="{{ $pb->icon ?: 'fa-solid fa-graduation-cap' }} text-ggsNavy"></i>
                            </div>
                            <h3 class="font-bold text-lg sm:text-xl text-ggsNavy mb-2.5 leading-snug font-battambang">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $pb->textFor('title', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $pb->textFor('title', 'en') }}</span>
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $pb->textFor('body', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $pb->textFor('body', 'en') }}</span>
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-ggsNavy bg-amber-50 px-3 py-1 rounded-full border border-amber-200/80">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $pb->textFor('subtitle', 'km') ?: 'សសរស្តម្ភយុទ្ធសាស្ត្រ' }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $pb->textFor('subtitle', 'en') ?: 'Strategic Pillar' }}</span>
                            </span>
                            <i class="fa-solid fa-circle-check text-ggsGold text-base"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ACCELERATED LEARNING PROGRAM SECTION -->
    <section id="accelerated" class="py-20 sm:py-28 bg-white">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                
                <div class="lg:col-span-7 space-y-5">
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-ggsGold/20 text-ggsNavy text-xs sm:text-sm font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-bolt text-ggsGold text-sm"></i>
                        <span>
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $alpBlock?->textFor('subtitle', 'km') ?: 'កម្មវិធីពិសេសសម្រាប់សិស្សលើសអាយុ' }}</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $alpBlock?->textFor('subtitle', 'en') ?: 'Accelerated Learning' }}</span>
                        </span>
                    </div>

                    <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy leading-tight">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $alpBlock?->textFor('title', 'km') ?: 'ថ្នាក់រៀនពន្លឿន (Accelerated Learning Program)' }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $alpBlock?->textFor('title', 'en') ?: 'Accelerated Learning Program (ALP)' }}</span>
                    </h2>

                    <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $alpBlock?->textFor('body', 'km') }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $alpBlock?->textFor('body', 'en') }}</span>
                    </p>

                    @if($alpBlock && !empty($alpBlock->extra['levels']))
                        <div class="space-y-3.5 pt-2">
                            @foreach($alpBlock->extra['levels'] as $lvl)
                                <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/90 shadow-sm hover:border-ggsGold/60 transition-colors">
                                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-ggsNavy text-ggsGold flex items-center justify-center font-black text-base sm:text-lg shrink-0 shadow-sm">
                                        {{ $lvl['code'] }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base sm:text-lg text-ggsNavy mb-1 font-battambang">
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $lvl['title_km'] ?? $lvl['title_en'] ?? '' }}</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $lvl['title_en'] ?? $lvl['title_km'] ?? '' }}</span>
                                        </h4>
                                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $lvl['desc_km'] ?? $lvl['desc_en'] ?? '' }}</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $lvl['desc_en'] ?? $lvl['desc_km'] ?? '' }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-5 relative">
                    <div class="rounded-3xl overflow-hidden shadow-xl border-4 border-white">
                        <img src="{{ $alpBlock?->image ?: 'https://6af3e067969c6488eb23b43f.imgix.net/sandbox/Gemini_Generated_Image_9xzddb9xzddb9xzd.jpg' }}" 
                             alt="GGS Accelerated Learning Classroom" class="w-full h-[460px] sm:h-[500px] object-cover">
                    </div>
                    @if($alpBlock && !empty($alpBlock->extra['badge_stat']))
                        <div class="absolute -bottom-6 -left-6 bg-ggsNavy text-white p-5 sm:p-6 rounded-2xl shadow-xl max-w-xs border-2 border-ggsGold/50">
                            <div class="text-ggsGold font-black text-2xl sm:text-3xl mb-1">{{ $alpBlock->extra['badge_stat'] }}</div>
                            <div class="text-xs text-slate-200 leading-relaxed font-medium">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $alpBlock->extra['badge_text_km'] ?? $alpBlock->extra['badge_text_en'] ?? '' }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $alpBlock->extra['badge_text_en'] ?? $alpBlock->extra['badge_text_km'] ?? '' }}</span>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- GRADE 6 SERVICE LEARNING UNIT SECTION -->
    <section id="service-learning" class="py-20 sm:py-28 bg-slate-900 text-white">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-block text-ggsGold font-bold text-xs sm:text-sm uppercase tracking-widest bg-white/10 px-5 py-2 rounded-full border border-ggsGold/30">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $serviceMain?->textFor('subtitle', 'km') ?: 'កម្មវិធីសិក្សាផ្អែកលើការអនុវត្ត' }}</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $serviceMain?->textFor('subtitle', 'en') ?: 'Hands-on Service Project' }}</span>
                </span>
                <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $serviceMain?->textFor('title', 'km') ?: 'កម្មវិធីសិក្សាអប់រំបម្រើសហគមន៍ (ថ្នាក់ទី៦)' }}</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $serviceMain?->textFor('title', 'en') ?: 'Grade 6 Community Service Learning Unit' }}</span>
                </h2>
                <p class="text-slate-300 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $serviceMain?->textFor('body', 'km') }}</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $serviceMain?->textFor('body', 'en') }}</span>
                </p>
            </div>

            <!-- 10 Lesson Timeline Highlights -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 mb-14">
                <div class="bg-slate-800/90 p-6 rounded-2xl border border-slate-700/80 shadow-md">
                    <div class="text-ggsGold font-bold text-xs uppercase tracking-widest mb-2.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">មេរៀនទី ១ - ២</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Lessons 1 - 2</span>
                    </div>
                    <h4 class="font-bold text-base sm:text-lg text-white mb-2 leading-snug font-battambang">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ការវាយតម្លៃតម្រូវការសហគមន៍</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Community Needs Assessment</span>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សិស្សចុះអង្កេត និងកំណត់បញ្ហាប្រឈមក្នុងសាលា ឬភូមិ។</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Students investigate and assess community challenges in local neighborhoods.</span>
                    </p>
                </div>

                <div class="bg-slate-800/90 p-6 rounded-2xl border border-slate-700/80 shadow-md">
                    <div class="text-ggsGold font-bold text-xs uppercase tracking-widest mb-2.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">មេរៀនទី ៣ - ៥</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Lessons 3 - 5</span>
                    </div>
                    <h4 class="font-bold text-base sm:text-lg text-white mb-2 leading-snug font-battambang">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ការរៀបចំគម្រោងក្រុម & បុគ្គល</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Individual & Team Planning</span>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">រៀបចំផែនការសកម្មភាព ថវិកាម៉ោង និងសម្ភារប្រកបដោយការទទួលខុសត្រូវ។</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Develop structured project timelines, team roles, and resource allocations.</span>
                    </p>
                </div>

                <div class="bg-slate-800/90 p-6 rounded-2xl border border-slate-700/80 shadow-md">
                    <div class="text-ggsGold font-bold text-xs uppercase tracking-widest mb-2.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">មេរៀនទី ៦ - ៨</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Lessons 6 - 8</span>
                    </div>
                    <h4 class="font-bold text-base sm:text-lg text-white mb-2 leading-snug font-battambang">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ការចុះអនុវត្តសកម្មភាពផ្ទាល់</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Field Implementation</span>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ដាំដើមឈើ ចូលរួមសម្អាតសាលា និងចែករំលែកបំណិនដល់ប្អូនៗ។</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Plant trees, conduct campus beautification, and mentor younger peers.</span>
                    </p>
                </div>

                <div class="bg-slate-800/90 p-6 rounded-2xl border border-slate-700/80 shadow-md">
                    <div class="text-ggsGold font-bold text-xs uppercase tracking-widest mb-2.5">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">មេរៀនទី ៩ - ១០</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Lessons 9 - 10</span>
                    </div>
                    <h4 class="font-bold text-base sm:text-lg text-white mb-2 leading-snug font-battambang">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ពិធីប្រគល់ស្នាដៃ & ពិចារណា</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Dedication & Reflection</span>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ថ្លែងសន្ទរកថា ប្រគល់គម្រោងជូនសហគមន៍ និងទទួលប័ណ្ណសរសើរ។</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Deliver closing presentations, project handover, and personal reflection.</span>
                    </p>
                </div>
            </div>

            <!-- Visual Showcase -->
            <div class="grid sm:grid-cols-3 gap-6 text-center">
                @foreach($serviceShowcases as $sc)
                    <div class="rounded-2xl overflow-hidden border border-slate-700 bg-slate-800/80 shadow-lg group">
                        <img src="{{ $sc->image }}" alt="{{ $sc->text('title') }}" class="w-full h-48 sm:h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="p-5">
                            <h5 class="font-bold text-base text-white mb-1.5 font-battambang">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $sc->textFor('title', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $sc->textFor('title', 'en') }}</span>
                            </h5>
                            @if($sc->body)
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $sc->textFor('body', 'km') }}</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $sc->textFor('body', 'en') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- GGS ACTION CLUBS SECTION -->
    <section class="py-20 sm:py-28 bg-slate-50 border-b border-slate-200">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-block text-ggsNavy font-bold text-xs sm:text-sm uppercase tracking-widest bg-white px-5 py-2 rounded-full border border-slate-200 shadow-sm">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សកម្មភាពក្រៅម៉ោងសិក្សា</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Co-Curricular Activities</span>
                </span>
                <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy leading-tight">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ក្លឹបសកម្មភាពសិក្សា GGS Action Clubs</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">GGS Action Clubs</span>
                </h2>
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ផ្តល់ឱកាសឱ្យសិស្សអភិវឌ្ឍចំណង់ចំណូលចិត្ត បំណិន និងចំណាប់អារម្មណ៍ផ្ទាល់ខ្លួន។</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Empowering students to explore personal talents, athletic leadership, and artistic expression.</span>
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($actionClubs as $club)
                    <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 text-ggsNavy flex items-center justify-center text-xl font-bold mb-4 shadow-sm">
                                <i class="{{ $club->icon ?: 'fa-solid fa-shapes' }}"></i>
                            </div>
                            <h4 class="font-bold text-lg sm:text-xl text-ggsNavy mb-2 leading-snug font-battambang">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $club->textFor('title', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $club->textFor('title', 'en') }}</span>
                            </h4>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $club->textFor('body', 'km') }}</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $club->textFor('body', 'en') }}</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- BRANCHES SECTION -->
    <section id="branches" class="py-20 sm:py-28 bg-white border-b border-slate-200">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-block text-ggsNavy font-bold text-xs sm:text-sm uppercase tracking-widest bg-ggsNavy/10 px-5 py-2 rounded-full border border-ggsNavy/15">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សាខាសាលារៀន GGS</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Our School Branches</span>
                </span>
                <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy leading-tight">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ស្វែងរកទីតាំងសិក្សាជិតលោកអ្នក</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Explore Campus Locations</span>
                </h2>
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">បរិវេណសាលាធំទូលាយ បំពាក់ដោយឧបករណ៍សិក្សាទំនើប សុវត្ថិភាព និងបរិស្ថានបៃតង។</span>
                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Spacious campuses equipped with state-of-the-art facilities, child safety, and green environments.</span>
                </p>
            </div>

            <!-- Interactive Campus Map & Location Showcase -->
            <div class="mb-12 bg-slate-50 rounded-3xl border border-slate-200 overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                <div class="grid lg:grid-cols-12 gap-0 items-stretch">
                    <!-- Satellite Map Image with Link -->
                    <div class="lg:col-span-8 relative overflow-hidden group">
                        <a href="https://maps.app.goo.gl/wR2Gct7vMwnT6T9H8" target="_blank" rel="noopener noreferrer" class="block h-full relative cursor-pointer" title="Open Global Generation School on Google Maps">
                            <img src="{{ asset('images/campus-map.jpg') }}" alt="Global Generation School Satellite Campus Map" class="w-full h-80 sm:h-96 lg:h-full min-h-[340px] object-cover group-hover:scale-105 transition-transform duration-700">
                            
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-900/10 to-transparent group-hover:from-slate-950/80 transition-colors"></div>

                            <!-- Floating Google Maps Pill Badge -->
                            <div class="absolute top-4 left-4 sm:top-6 sm:left-6 bg-white/95 backdrop-blur-md px-4 py-2 rounded-xl shadow-lg border border-slate-200 flex items-center gap-2.5 text-xs sm:text-sm font-bold text-ggsNavy group-hover:bg-ggsGold group-hover:text-ggsNavy transition-all transform group-hover:scale-105">
                                <i class="fa-solid fa-map-location-dot text-red-600 group-hover:text-ggsNavy text-base"></i>
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">មើលលើ Google Maps</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Open in Google Maps</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-75"></i>
                            </div>

                            <!-- Bottom Floating Hint -->
                            <div class="absolute bottom-4 left-4 right-4 sm:bottom-6 sm:left-6 sm:right-6 flex items-center justify-between text-white pointer-events-none">
                                <div class="flex items-center gap-2 text-xs sm:text-sm font-medium bg-slate-900/80 backdrop-blur-sm px-3.5 py-1.5 rounded-lg border border-white/20">
                                    <i class="fa-solid fa-hand-pointer text-ggsGold animate-pulse"></i>
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចុចលើរូបភាពដើម្បីស្វែងរកផ្លូវធ្វើដំណើរ</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Click image for live directions</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Campus Location Details -->
                    <div class="lg:col-span-4 p-6 sm:p-8 lg:p-10 flex flex-col justify-between bg-white border-t lg:border-t-0 lg:border-l border-slate-200">
                        <div class="space-y-6">
                            <div>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider mb-3">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                    <span>Main Campus • សាខាកណ្តាល</span>
                                </span>
                                <h3 class="font-battambang text-2xl sm:text-3xl font-extrabold text-ggsNavy leading-tight">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សាលាជំនាន់សកល (GGS)</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Global Generation School</span>
                                </h3>
                                <p class="text-xs sm:text-sm text-ggsGold font-bold uppercase tracking-wider mt-1">Siem Reap City Campus</p>
                            </div>

                            <div class="space-y-4 text-xs sm:text-sm text-slate-600">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-ggsNavy/5 flex items-center justify-center text-ggsNavy shrink-0 mt-0.5">
                                        <i class="fa-solid fa-location-dot text-red-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-xs sm:text-sm">
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">អាសយដ្ឋានទីតាំង៖</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Address:</span>
                                        </div>
                                        <p class="leading-relaxed mt-0.5">
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ភូមិសាលាកំរើក សង្កាត់សាលាកំរើក ក្រុងសៀមរាប ខេត្តសៀមរាប</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Sala Kamreauk Village, Sangkat Sala Kamreauk, Siem Reap City, Cambodia</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-ggsNavy/5 flex items-center justify-center text-ggsNavy shrink-0 mt-0.5">
                                        <i class="fa-solid fa-phone text-ggsGold"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-xs sm:text-sm">
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទំនាក់ទំនងទូរស័ព្ទ៖</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Contact Phone:</span>
                                        </div>
                                        <p class="leading-relaxed mt-0.5 font-medium">088 6666 288 / 012 452 837 / 092 820 249</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-ggsNavy/5 flex items-center justify-center text-ggsNavy shrink-0 mt-0.5">
                                        <i class="fa-solid fa-clock text-ggsNavy"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-xs sm:text-sm">
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ម៉ោងធ្វើការ៖</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Office Hours:</span>
                                        </div>
                                        <p class="leading-relaxed mt-0.5">
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ច័ន្ទ - សៅរ៍: ៧:០០ ព្រឹក - ៥:០០ ល្ងាច</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Mon - Sat: 7:00 AM - 5:00 PM</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-slate-100 flex flex-col sm:flex-row lg:flex-col gap-3">
                            <a href="https://maps.app.goo.gl/wR2Gct7vMwnT6T9H8" target="_blank" rel="noopener noreferrer" class="w-full py-3.5 px-5 bg-ggsNavy hover:bg-slate-800 text-white font-bold rounded-xl shadow-md text-center flex items-center justify-center gap-2.5 text-xs sm:text-sm transition-all transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-diamond-turn-right text-ggsGold text-base"></i>
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">បើកមើលទីតាំង Google Maps</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Open Location in Google Maps</span>
                            </a>
                            <button onclick="openInquiryModal()" class="w-full py-3 px-5 bg-ggsGold hover:bg-ggsGoldHover text-ggsNavy font-bold rounded-xl shadow-sm text-center flex items-center justify-center gap-2 text-xs sm:text-sm transition-all cursor-pointer">
                                <i class="fa-regular fa-calendar-check text-sm"></i>
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ចុះឈ្មោះទស្សនាសាលា</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Book Campus Tour</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if($branches->isNotEmpty())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($branches as $branch)
                    <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            @if($branch->image)
                                <img src="{{ str_starts_with($branch->image,'school/') ? asset('storage/'.$branch->image) : asset($branch->image) }}" class="w-full h-52 sm:h-56 object-cover" alt="{{ $branch->text('name') }}">
                            @else
                                <div class="w-full h-52 sm:h-56 bg-gradient-to-r from-ggsNavy to-ggsNavyDark flex items-center justify-center text-ggsGold text-5xl">
                                    <i class="fa-solid fa-school"></i>
                                </div>
                            @endif
                            <div class="p-6 sm:p-7">
                                <h3 class="font-bold text-xl sm:text-2xl text-ggsNavy mb-3 leading-snug font-battambang">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $branch->name['km'] ?? $branch->name['en'] }}</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $branch->name['en'] ?? $branch->name['km'] }}</span>
                                </h3>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed line-clamp-3 mb-4">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $branch->description['km'] ?? $branch->description['en'] }}</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $branch->description['en'] ?? $branch->description['km'] }}</span>
                                </p>
                                <div class="space-y-2 text-xs sm:text-sm text-slate-500 border-t border-slate-100 pt-4">
                                    @if($branch->text('address'))
                                        <p class="flex items-start gap-2.5">
                                            <i class="fa-solid fa-location-dot text-ggsGold mt-0.5 text-sm"></i> 
                                            <span class="leading-relaxed">
                                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $branch->address['km'] ?? $branch->address['en'] }}</span>
                                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $branch->address['en'] ?? $branch->address['km'] }}</span>
                                            </span>
                                        </p>
                                    @endif
                                    @if($branch->phone)
                                        <p class="flex items-center gap-2.5"><i class="fa-solid fa-phone text-ggsGold text-xs"></i> <span>{{ $branch->phone }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="p-6 sm:p-7 pt-0">
                            <a href="{{ route('branch', $branch->slug) }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-ggsNavy hover:text-ggsGold transition-colors">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ស្វែងយល់បន្ថែមអំពីសាខានេះ</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Explore Campus Details</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- NEWS & UPDATES SECTION -->
    <section id="news" class="py-20 sm:py-28 bg-slate-50 border-b border-slate-200">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-5 mb-14">
                <div>
                    <span class="inline-block text-ggsNavy font-bold text-xs sm:text-sm uppercase tracking-widest bg-white px-5 py-2 rounded-full border border-slate-200 shadow-sm">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $newsBlock?->textFor('subtitle', 'km') ?: 'ព័ត៌មាន & សកម្មភាព' }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $newsBlock?->textFor('subtitle', 'en') ?: 'Latest News & Updates' }}</span>
                    </span>
                    <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy mt-2.5 leading-tight">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $newsBlock?->textFor('title', 'km') ?: 'ព្រឹត្តិការណ៍ថ្មីៗរបស់សាលា' }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $newsBlock?->textFor('title', 'en') ?: 'School News & Stories' }}</span>
                    </h2>
                    @if($newsBlock)
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed max-w-2xl mt-2">
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $newsBlock->textFor('body', 'km') }}</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $newsBlock->textFor('body', 'en') }}</span>
                        </p>
                    @endif
                </div>
                <a href="{{ $newsBlock?->link_url ?: route('news') }}" class="px-5 py-2.5 bg-white hover:bg-slate-100 text-ggsNavy font-bold rounded-xl border border-slate-200 shadow-sm text-xs sm:text-sm flex items-center gap-2.5 transition-all">
                    <span>
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $newsBlock?->textFor('link_text', 'km') ?: 'មើលព័ត៌មានទាំងអស់' }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $newsBlock?->textFor('link_text', 'en') ?: 'View All News' }}</span>
                    </span>
                    <i class="fa-solid fa-arrow-right text-ggsGold text-xs"></i>
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($posts as $post)
                    @php
                        $homePostImg = method_exists($post, 'imageUrl') ? $post->imageUrl() : ($post->image ? (str_starts_with($post->image,'school/') ? asset('storage/'.$post->image) : asset($post->image)) : null);
                        $homePostCat = $post->category ?? 'Campus News';
                        $homePostDate = $post->published_at ? $post->published_at->format('d M Y') : ($post->created_at ? $post->created_at->format('d M Y') : date('d M Y'));
                        $homePostSummary = method_exists($post, 'text') && $post->text('summary') ? $post->text('summary') : (method_exists($post, 'text') ? $post->text('body') : '');
                    @endphp
                    <article class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                        <div>
                            <div class="relative overflow-hidden aspect-[16/10] bg-slate-900">
                                @if($homePostImg)
                                    <img src="{{ $homePostImg }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $post->text('title') }}" loading="lazy">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#0B2240] to-slate-800 text-white p-6 text-center">
                                        <i class="fa-solid fa-newspaper text-3xl text-ggsGold mb-2"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-300">GGS News</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-ggsNavy/90 backdrop-blur-md text-ggsGold border border-ggsGold/30">
                                        {{ $homePostCat }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 sm:p-7">
                                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
                                    <i class="fa-regular fa-calendar text-ggsGold"></i>
                                    <span>{{ $homePostDate }}</span>
                                    @if(!empty($post->read_time))
                                        <span>•</span>
                                        <span>{{ $post->read_time }}</span>
                                    @endif
                                </div>
                                <h3 class="font-bold text-lg sm:text-xl text-ggsNavy group-hover:text-ggsGold transition-colors mb-2.5 leading-snug line-clamp-2 font-battambang">
                                    <a href="{{ route('article', $post->slug) }}">
                                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ (is_array($post->title) && !empty($post->title['km'])) ? $post->title['km'] : $post->text('title') }}</span>
                                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ (is_array($post->title) && !empty($post->title['en'])) ? $post->title['en'] : $post->text('title') }}</span>
                                    </a>
                                </h3>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed line-clamp-3">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ (is_array($post->summary) && !empty($post->summary['km'])) ? $post->summary['km'] : ((is_array($post->body) && !empty($post->body['km'])) ? \Illuminate\Support\Str::limit(strip_tags($post->body['km']), 140) : $homePostSummary) }}</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ (is_array($post->summary) && !empty($post->summary['en'])) ? $post->summary['en'] : ((is_array($post->body) && !empty($post->body['en'])) ? \Illuminate\Support\Str::limit(strip_tags($post->body['en']), 140) : $homePostSummary) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="p-6 sm:p-7 pt-0 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('article', $post->slug) }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-ggsNavy group-hover:text-ggsGold transition-colors">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">អានលម្អិត</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Read Story</span>
                                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <span class="text-[11px] text-slate-400 font-semibold">GGS Official</span>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                        <p class="text-sm sm:text-base text-slate-500">
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ព័ត៌មានថ្មីៗនឹងត្រូវបានផ្សព្វផ្សាយក្នុងពេលឆាប់ៗនេះ។</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Updates are coming soon.</span>
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- GALLERY SECTION (IF AVAILABLE) -->
    @if(isset($gallery) && $gallery->isNotEmpty())
        <section class="py-20 sm:py-28 bg-white border-b border-slate-200">
            <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
                <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                    <span class="inline-block text-ggsNavy font-bold text-xs sm:text-sm uppercase tracking-widest bg-ggsNavy/10 px-5 py-2 rounded-full border border-ggsNavy/15">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">កម្រងរូបភាពជីវិតសាលា</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Campus Life Gallery</span>
                    </span>
                    <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy leading-tight">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សកម្មភាព និងបរិយាកាសសិក្សា</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Moments Inside Our Community</span>
                    </h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-5 sm:gap-6">
                    @foreach($gallery as $item)
                        @if($item->image)
                            <div class="rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all h-64 sm:h-72">
                                <img class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500" 
                                     src="{{ str_starts_with($item->image,'school/') ? asset('storage/'.$item->image) : asset($item->image) }}" 
                                     alt="{{ $item->text('title') }}">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- CHILD PROTECTION & SAFETY SECTION -->
    <section id="child-protection" class="py-20 sm:py-28 bg-white">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="bg-slate-900 text-white rounded-3xl p-8 sm:p-10 lg:p-14 shadow-xl border border-slate-800 relative overflow-hidden">
                <div class="max-w-4xl space-y-6 relative z-10">
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-rose-500/20 text-rose-300 text-xs sm:text-sm font-bold uppercase tracking-wider">
                        <i class="{{ $cpBlock?->icon ?: 'fa-solid fa-shield-halved' }} text-sm"></i>
                        <span>
                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $cpBlock?->textFor('subtitle', 'km') ?: 'សុវត្ថិភាពកុមារជាអាទិភាពចម្បង' }}</span>
                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $cpBlock?->textFor('subtitle', 'en') ?: 'Child Protection First' }}</span>
                        </span>
                    </div>

                    <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $cpBlock?->textFor('title', 'km') ?: 'នយោបាយការពារកុមារ និងសុវត្ថិភាព (UNICEF / MoEYS Aligned)' }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $cpBlock?->textFor('title', 'en') ?: 'Child Protection & Safety Policy Framework (UNICEF / MoEYS Aligned)' }}</span>
                    </h2>

                    <p class="text-slate-200 text-sm sm:text-base lg:text-lg leading-relaxed">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $cpBlock?->textFor('body', 'km') }}</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $cpBlock?->textFor('body', 'en') }}</span>
                    </p>

                    @if($cpBlock && !empty($cpBlock->extra['points']))
                        <div class="grid sm:grid-cols-3 gap-6 pt-6 border-t border-slate-800">
                            @foreach($cpBlock->extra['points'] as $pt)
                                <div class="space-y-2">
                                    <div class="font-bold text-base sm:text-lg text-ggsGold flex items-center gap-2.5">
                                        <i class="fa-solid fa-check-double text-sm"></i> 
                                        <span>
                                            <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $pt['title_km'] ?? $pt['title_en'] ?? '' }}</span>
                                            <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $pt['title_en'] ?? $pt['title_km'] ?? '' }}</span>
                                        </span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">{{ $pt['desc_km'] ?? $pt['desc_en'] ?? '' }}</span>
                                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">{{ $pt['desc_en'] ?? $pt['desc_km'] ?? '' }}</span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT FORM SECTION -->
    <section id="contact-section" class="py-20 sm:py-28 bg-slate-100/80 border-t border-slate-200">
        <div class="max-w-[1560px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 items-start">
                
                <div class="lg:col-span-5 space-y-6">
                    <span class="inline-block text-ggsNavy font-bold text-xs sm:text-sm uppercase tracking-widest bg-white px-5 py-2 rounded-full border border-slate-200 shadow-sm">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទំនាក់ទំនងមកកាន់យើងខ្ញុំ</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Get In Touch</span>
                    </span>
                    <h2 class="font-battambang text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ggsNavy leading-tight">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">តើលោកអ្នកមានសំណួរអ្វីដែរឬទេ?</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Have Questions? We'd Love to Hear From You.</span>
                    </h2>
                    <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed">
                        <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សូមទាក់ទងមកកាន់ការិយាល័យរដ្ឋបាលនៃសាលាជំនាន់សកល (GGS) ក្រុងសៀមរាប ឬបំពេញទម្រង់បែបបទនៅទីនេះដើម្បីទទួលបានព័ត៌មានលម្អិត។</span>
                        <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Contact our admissions office or submit an inquiry form below to speak with an educational counselor.</span>
                    </p>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-ggsNavy border border-slate-200/80 shrink-0 text-lg">
                                <i class="fa-solid fa-phone text-ggsGold"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-sm sm:text-base">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">លេខទូរស័ព្ទ៖</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Phone Numbers:</span>
                                </span>
                                <div class="text-slate-600 text-xs sm:text-sm font-medium mt-0.5">012 452 837 / 092 820 249 / 088 666 6288</div>
                            </div>
                        </div>

                        <a href="https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox?compose=new" target="_blank" rel="noopener noreferrer" class="flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-ggsNavy border border-slate-200/80 shrink-0 text-lg group-hover:bg-ggsNavy group-hover:text-white transition-colors">
                                <i class="fa-solid fa-envelope text-ggsGold"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-sm sm:text-base group-hover:text-ggsGold transition-colors flex items-center gap-2">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">អ៊ីមែលផ្ទាល់៖</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Direct Email:</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-ggsGold"></i>
                                </span>
                                <div class="text-slate-600 text-xs sm:text-sm font-medium mt-0.5 group-hover:text-ggsNavy transition-colors">{{ \App\Models\Setting::valueFor('contact_email', 'ggschool2020@gmail.com') }}</div>
                            </div>
                        </a>

                        <a href="https://maps.app.goo.gl/wR2Gct7vMwnT6T9H8" target="_blank" rel="noopener noreferrer" class="flex items-start gap-4 group">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-ggsNavy border border-slate-200/80 shrink-0 text-lg mt-0.5 group-hover:bg-ggsNavy group-hover:text-white transition-colors">
                                <i class="fa-solid fa-location-dot text-red-600 text-lg group-hover:scale-110 transition-transform"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-sm sm:text-base group-hover:text-ggsGold transition-colors flex items-center gap-2">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ទីតាំងសាលា៖</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Campus Location:</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-ggsGold"></i>
                                </span>
                                <div class="text-slate-600 text-xs sm:text-sm font-medium mt-0.5 leading-relaxed group-hover:text-slate-800">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ភូមិសាលាកំរើក ក្រុងសៀមរាប ខេត្តសៀមរាប (បើកមើលលើ Google Maps)</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Sala Kamreauk Village, Siem Reap City, Cambodia (View on Google Maps)</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <form id="contact-form" action="{{ route('contact') }}" method="post" class="bg-white rounded-2xl p-6 sm:p-9 shadow-xl border border-slate-200/90 space-y-4 text-sm">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block font-bold text-slate-700 text-xs sm:text-sm mb-1.5">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ឈ្មោះរបស់លោកអ្នក *</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Your Full Name *</span>
                                </label>
                                <input type="text" id="name" name="name" required maxlength="100" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm">
                            </div>

                            <div>
                                <label for="email" class="block font-bold text-slate-700 text-xs sm:text-sm mb-1.5">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">អាសយដ្ឋានអ៊ីមែល *</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Email Address *</span>
                                </label>
                                <input type="email" id="email" name="email" required maxlength="190" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block font-bold text-slate-700 text-xs sm:text-sm mb-1.5">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">លេខទូរស័ព្ទ</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Phone Number</span>
                                </label>
                                <input type="tel" id="phone" name="phone" maxlength="40" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm">
                            </div>

                            <div>
                                <label for="branch_id" class="block font-bold text-slate-700 text-xs sm:text-sm mb-1.5">
                                    <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ជ្រើសរើសសាខា (ស្រេចចិត្ត)</span>
                                    <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Select Branch (Optional)</span>
                                </label>
                                <select id="branch_id" name="branch_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm bg-white">
                                    <option value="">— All Campuses / General —</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->text('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block font-bold text-slate-700 text-xs sm:text-sm mb-1.5">
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">សារ ឬ សំណួររបស់លោកអ្នក *</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">How can we help? (Message) *</span>
                            </label>
                            <textarea id="message" name="message" rows="4" required maxlength="3000" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-ggsNavy focus:outline-none text-sm"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 sm:py-4 bg-ggsGold hover:bg-ggsGoldHover text-ggsNavy font-bold rounded-xl shadow-lg transition-all text-sm sm:text-base uppercase tracking-wider flex items-center justify-center gap-2.5 cursor-pointer">
                                <i class="fa-solid fa-paper-plane text-base"></i>
                                <span class="lang-km {{ app()->getLocale() === 'km' ? '' : 'hidden' }}">ផ្ញើសារមកកាន់យើងខ្ញុំ</span>
                                <span class="lang-en {{ app()->getLocale() !== 'km' ? '' : 'hidden' }}">Send Message</span>
                            </button>
                            <div id="contact-feedback" role="status" class="mt-3 text-center font-bold text-sm"></div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection
