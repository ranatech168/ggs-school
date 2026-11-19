@extends('admin.layout')

@section('title', $item->exists ? 'Edit Block' : 'New Block')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.blocks.index', ['section' => $item->section]) }}">← Back to home blocks</a>
    <h1 class="mt-3">{{ $item->exists ? 'Edit Block: ' . $item->key : 'Create New Block' }}</h1>
    <p class="text-secondary">Configure this frontend layout block. English title is required; Khmer and Chinese translations can be added anytime.</p>
</div>

<form class="ajax-form" method="post" enctype="multipart/form-data" 
      action="{{ $item->exists ? route('admin.blocks.update', $item) : route('admin.blocks.store') }}">
    @csrf

    <div class="panel mb-4">
        <h2 class="fs-5 mb-3">1. Block Identification & Placement</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="section">Target Section *</label>
                <select class="form-select" name="section" id="section" required>
                    @foreach($sections as $secKey => $secLabel)
                        <option value="{{ $secKey }}" @selected($item->section === $secKey)>
                            {{ $secLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="key">Unique Key Identifier *</label>
                <input class="form-control" id="key" name="key" value="{{ $item->key }}" 
                       required pattern="[A-Za-z0-9_-]+" placeholder="e.g. pillar_stem, hero_main">
                <small class="text-muted">Unique identifier used by frontend templates.</small>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="icon">FontAwesome Icon Class</label>
                <input class="form-control" id="icon" name="icon" value="{{ $item->icon }}" 
                       placeholder="e.g. fa-solid fa-atom, fa-solid fa-heart">
                <small class="text-muted">Icon class for cards (optional).</small>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <label class="form-label" for="link_url">Button / Link URL</label>
                <input class="form-control" id="link_url" name="link_url" value="{{ $item->link_url }}" 
                       placeholder="e.g. #pillars, /contact, https://...">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="link_text_en">Button / Link Label (English)</label>
                <input class="form-control" id="link_text_en" name="link_text[en]" 
                       value="{{ $item->link_text['en'] ?? '' }}" placeholder="e.g. Learn More">
            </div>
        </div>
    </div>

    <!-- Multilingual Translations -->
    <div class="panel mb-4">
        <h2 class="fs-5 mb-3">2. Content & Translations</h2>

        @foreach(['en' => 'English (Primary)', 'km' => 'Khmer (ភាសាខ្មែរ - Front Page Display)', 'zh' => 'Chinese (中文)'] as $locale => $label)
            <div class="translation mb-4 p-3 border rounded {{ $locale === 'km' ? 'bg-warning-subtle border-warning' : 'bg-light-subtle' }}">
                <div class="d-flex align-items-center justify-between mb-3">
                    <h3 class="fs-6 fw-bold text-primary mb-0">{{ $label }}</h3>
                    @if($locale === 'km')
                        <span class="badge bg-warning text-dark fw-bold">🇰🇭 Khmer Front Page Translation</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="title-{{ $locale }}">
                        Title / Main Headline {{ $locale === 'en' ? '*' : '' }}
                    </label>
                    <input class="form-control" id="title-{{ $locale }}" name="title[{{ $locale }}]" 
                           value="{{ $item->title[$locale] ?? '' }}" @required($locale === 'en') maxlength="300"
                           @if($locale === 'km') style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;" @endif>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="subtitle-{{ $locale }}">
                        Subtitle / Tagline / Badge Label
                    </label>
                    <input class="form-control" id="subtitle-{{ $locale }}" name="subtitle[{{ $locale }}]" 
                           value="{{ $item->subtitle[$locale] ?? '' }}" maxlength="300"
                           placeholder="e.g. Patriotic Heart, Global Mind / MoEYS Standard"
                           @if($locale === 'km') style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;" @endif>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="body-{{ $locale }}">
                        Description / Paragraph Content
                    </label>
                    <textarea class="form-control" rows="3" id="body-{{ $locale }}" 
                              name="body[{{ $locale }}]"
                              @if($locale === 'km') style="font-family: 'Kantumruy Pro', 'Battambang', sans-serif;" @endif>{{ $item->body[$locale] ?? '' }}</textarea>
                </div>

                @if($locale !== 'en')
                    <div class="mb-1">
                        <label class="form-label" for="link_text-{{ $locale }}">
                            Button / Link Label ({{ $label }})
                        </label>
                        <input class="form-control" id="link_text-{{ $locale }}" name="link_text[{{ $locale }}]" 
                               value="{{ $item->link_text[$locale] ?? '' }}">
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Media & Advanced Data -->
    <div class="panel mb-4">
        <h2 class="fs-5 mb-3">3. Media & Extra Structured Data</h2>

        @include('admin.partials.image')

        <div class="mt-4">
            <label class="form-label" for="extra_raw">Structured Data (JSON / Optional)</label>
            <textarea class="form-control font-monospace" rows="5" id="extra_raw" name="extra_raw" 
                      placeholder='{"value": "100%", "levels": [...], "points": [...]}'>{{ !empty($item->extra) ? json_encode($item->extra, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '' }}</textarea>
            <small class="text-muted">
                Used for advanced block structures such as stat numbers (<code>{"value": "6"}</code>), highlights array, or timeline bullet points. Leave blank if not needed.
            </small>
        </div>

        <div class="row g-3 mt-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label" for="sort_order">Sort Order</label>
                <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" 
                       value="{{ $item->sort_order ?? 0 }}" required>
            </div>
            <div class="col-md-4 pt-4">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="published" value="1" @checked($item->published ?? true)> 
                    <span class="fw-bold">Publish this block on website</span>
                </label>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t">
            <button class="btn btn-primary px-4">
                {{ $item->exists ? 'Update block' : 'Save block' }}
            </button>
            <a href="{{ route('admin.blocks.index', ['section' => $item->section]) }}" class="btn btn-light ms-2">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection

