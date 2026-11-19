@extends('admin.layout')

@section('title', 'Header (Hero Section)')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1 fw-bold text-dark">Header & Hero Section</h1>
        <p class="text-secondary mb-0">Update the main top hero banner, motto badge, headline (H2), subheadline, action buttons, and background image on the website home page.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Homepage
        </a>
    </div>
</div>

<form class="ajax-form" method="post" enctype="multipart/form-data" action="{{ route('admin.header.save') }}">
    @csrf

    <!-- SECTION 1: BADGE & MOTTO -->
    <div class="panel p-4 mb-4">
        <h2 class="fs-5 fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-certificate text-warning me-2"></i> 1. Top Motto Badge
        </h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold" for="icon">FontAwesome Icon Class</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="{{ $item->icon ?: 'fa-solid fa-heart-pulse' }}"></i></span>
                    <input class="form-control" id="icon" name="icon" value="{{ $item->icon ?: 'fa-solid fa-heart-pulse' }}" placeholder="e.g. fa-solid fa-heart-pulse">
                </div>
                <small class="text-muted">Icon shown inside the motto badge.</small>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" for="subtitle_en">Badge Motto (English) *</label>
                <input class="form-control" id="subtitle_en" name="subtitle[en]" value="{{ $item->subtitle['en'] ?? 'Patriotic Heart, Global Mind' }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" for="subtitle_km">
                    <span class="badge bg-warning text-dark me-1">🇰🇭 Khmer</span> Badge Motto (Khmer)
                </label>
                <input class="form-control" id="subtitle_km" name="subtitle[km]" value="{{ $item->subtitle['km'] ?? 'បេះដូងស្នេហាជាតិ គំនិតកម្រិតសកល' }}" style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;">
            </div>
        </div>
    </div>

    <!-- SECTION 2: MAIN HEADLINE (H2) & DESCRIPTION -->
    <div class="panel p-4 mb-4">
        <h2 class="fs-5 fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-heading text-primary me-2"></i> 2. Main Headline (H4) & Introduction Content
        </h2>

        <!-- English Content -->
        <div class="p-3 mb-3 border rounded bg-primary-subtle border-primary">
            <div class="d-flex align-items-center justify-between mb-2">
                <h3 class="fs-6 fw-bold text-primary mb-0">English Content</h3>
                <span class="badge bg-primary">Default Language</span>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="title_en">Main Headline (English) *</label>
                <input class="form-control form-control-lg fs-6" id="title_en" name="title[en]" 
                       value="{{ $item->title['en'] ?? '' }}" required maxlength="300"
                       placeholder="e.g. A brighter future starts here: Transformative 21st Century Bilingual & Digital Education">
                <small class="text-muted">Displays as the prominent center-aligned H4 headline on the home page.</small>
            </div>
            <div>
                <label class="form-label fw-semibold" for="body_en">Introduction Subheadline / Description (English)</label>
                <textarea class="form-control" rows="3" id="body_en" name="body[en]" 
                          placeholder="Comprehensive introduction about GGS school in English...">{{ $item->body['en'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- Khmer Content -->
        <div class="p-3 mb-3 border rounded bg-warning-subtle border-warning">
            <div class="d-flex align-items-center justify-between mb-2">
                <h3 class="fs-6 fw-bold text-dark mb-0">
                    <span class="badge bg-warning text-dark me-1">🇰🇭</span> Khmer Content (ភាសាខ្មែរ)
                </h3>
                <span class="badge bg-warning text-dark fw-bold">Front Page Display on Khmer Selection</span>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="title_km">ចំណងជើងធំ (Main Headline Khmer - H4)</label>
                <input class="form-control form-control-lg fs-6" id="title_km" name="title[km]" 
                       value="{{ $item->title['km'] ?? '' }}" maxlength="300"
                       placeholder="ឧទាហរណ៍៖ ការអប់រំប្រកបដោយគុណភាព និងការផ្លាស់ប្តូរឌីជីថល (អនាគតភ្លឺស្វាងចាប់ផ្តើមពីទីនេះ)"
                       style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;">
                <small class="text-muted">ចំណងជើង H4 កណ្តាលដែលបង្ហាញជាភាសាខ្មែរនៅលើទំព័រមុខ។</small>
            </div>
            <div>
                <label class="form-label fw-semibold" for="body_km">ខ្លឹមសារពិពណ៌នាសង្ខេប (Subheadline Khmer)</label>
                <textarea class="form-control" rows="3" id="body_km" name="body[km]" 
                          placeholder="ពិពណ៌នាអំពីចក្ខុវិស័យ និងកម្មវិធីអប់រំរបស់សាលាជាភាសាខ្មែរ..."
                          style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;">{{ $item->body['km'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- Chinese Content (Optional) -->
        <div class="p-3 border rounded bg-light-subtle">
            <h3 class="fs-6 fw-bold text-secondary mb-2">Chinese Content (中文 - Optional)</h3>
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small" for="title_zh">Headline (Chinese)</label>
                    <input class="form-control" id="title_zh" name="title[zh]" value="{{ $item->title['zh'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small" for="body_zh">Description (Chinese)</label>
                    <textarea class="form-control" rows="1" id="body_zh" name="body[zh]">{{ $item->body['zh'] ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: HERO BACKGROUND IMAGE -->
    <div class="panel p-4 mb-4">
        <h2 class="fs-5 fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-image text-success me-2"></i> 3. Hero Background Image
        </h2>
        
        <div class="mb-3">
            @include('admin.partials.image')
        </div>

        <div class="mb-2">
            <label class="form-label fw-semibold" for="image_url">Or Direct Image URL (External / Unsplash CDN)</label>
            <input class="form-control" id="image_url" name="image_url" 
                   value="{{ (filter_var($item->image, FILTER_VALIDATE_URL)) ? $item->image : '' }}" 
                   placeholder="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2000&auto=format&fit=crop">
            <small class="text-muted">If you upload a file above, it will take priority over this external URL.</small>
        </div>
    </div>

    <!-- SECTION 4: CALL TO ACTION BUTTONS -->
    <div class="panel p-4 mb-4">
        <h2 class="fs-5 fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-link text-info me-2"></i> 4. Action Buttons (CTA)
        </h2>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <span class="fw-bold text-primary">Primary Button (Opens Tour Booking / Admissions Modal):</span>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="cta_en">Button Label (English)</label>
                <input class="form-control" id="cta_en" name="cta_text[en]" 
                       value="{{ $item->extra['cta_text']['en'] ?? 'Admissions 2026-2027' }}" 
                       placeholder="e.g. Admissions 2026-2027">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="cta_km">
                    <span class="badge bg-warning text-dark me-1">🇰🇭 Khmer</span> Button Label (Khmer)
                </label>
                <input class="form-control" id="cta_km" name="cta_text[km]" 
                       value="{{ $item->extra['cta_text']['km'] ?? 'ចុះឈ្មោះចូលរៀន ២០២៦-២០២៧' }}" 
                       placeholder="ឧ. ចុះឈ្មោះចូលរៀន ២០២៦-២០២៧"
                       style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;">
            </div>
        </div>

        <div class="row g-3 pt-3 border-top">
            <div class="col-12">
                <span class="fw-bold text-primary">Secondary Button (Scroll / External Link):</span>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="link_url">Button Target URL</label>
                <input class="form-control" id="link_url" name="link_url" 
                       value="{{ $item->link_url ?: '#pillars' }}" 
                       placeholder="#pillars or /contact or https://...">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="link_text_en">Button Label (English)</label>
                <input class="form-control" id="link_text_en" name="link_text[en]" 
                       value="{{ $item->link_text['en'] ?? 'Explore 6 Pillars' }}" 
                       placeholder="e.g. Explore 6 Pillars">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="link_text_km">
                    <span class="badge bg-warning text-dark me-1">🇰🇭 Khmer</span> Button Label (Khmer)
                </label>
                <input class="form-control" id="link_text_km" name="link_text[km]" 
                       value="{{ $item->link_text['km'] ?? 'ស្វែងយល់សសរស្តម្ភទាំង៦' }}" 
                       placeholder="ឧ. ស្វែងយល់សសរស្តម្ភទាំង៦"
                       style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;">
            </div>
        </div>
    </div>

    <!-- SECTION 5: 3 QUICK HIGHLIGHTS (UNDER HERO) -->
    @php
        $highlights = $item->extra['highlights'] ?? [
            ['en' => 'MoEYS Recognized', 'km' => 'ទទួលស្គាល់ដោយ ក្រសួងអប់រំ'],
            ['en' => 'Hands-on STEM & AI', 'km' => 'រៀន STEM & AI ជាក់ស្តែង'],
            ['en' => '100% Child Safety', 'km' => 'សុវត្ថិភាពកុមារ១០០%'],
        ];
    @endphp
    <div class="panel p-4 mb-4">
        <h2 class="fs-5 fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-list-check text-warning me-2"></i> 5. Three Quick Highlights (Under Hero Headline)
        </h2>
        <p class="text-secondary small mb-3">Displays with checkmark badges under the hero action buttons on the home page.</p>

        @for($i = 0; $i < 3; $i++)
            <div class="row g-3 mb-3 p-3 bg-light rounded border align-items-center">
                <div class="col-auto">
                    <span class="badge bg-warning text-dark rounded-circle p-2">#{{ $i + 1 }}</span>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold" for="hl_{{ $i }}_en">Highlight #{{ $i + 1 }} (English)</label>
                    <input class="form-control" id="hl_{{ $i }}_en" name="highlights[{{ $i }}][en]" 
                           value="{{ $highlights[$i]['en'] ?? '' }}" placeholder="e.g. MoEYS Recognized">
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold" for="hl_{{ $i }}_km">
                        <span class="badge bg-warning text-dark me-1">🇰🇭 Khmer</span> Highlight #{{ $i + 1 }} (Khmer)
                    </label>
                    <input class="form-control" id="hl_{{ $i }}_km" name="highlights[{{ $i }}][km]" 
                           value="{{ $highlights[$i]['km'] ?? '' }}" placeholder="ឧ. ទទួលស្គាល់ដោយ ក្រសួងអប់រំ"
                           style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;">
                </div>
            </div>
        @endfor
    </div>

    <!-- SECTION 6: PUBLISH & SAVE -->
    <div class="panel p-4 mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <div class="form-check form-switch fs-6">
                <input class="form-check-input" type="checkbox" id="published" name="published" value="1" @checked($item->published ?? true)>
                <label class="form-check-label fw-bold ms-2" for="published">
                    Publish and display this Hero Section on the frontend
                </label>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                    <i class="fa-solid fa-floppy-disk me-1.5"></i> Save Header / Hero Section
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

