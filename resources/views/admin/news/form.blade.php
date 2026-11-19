@extends('admin.layout')

@section('title', $item->exists ? 'Edit News Story' : 'New News Story')

@section('content')
<div class="mb-4">
    <a href="{{ request('from_blocks') ? route('admin.blocks.index', ['section' => 'news']) : route('admin.news.index') }}" class="text-decoration-none text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to {{ request('from_blocks') ? 'Home Blocks' : 'News & Stories' }}
    </a>
    <h1 class="mt-3">{{ $item->exists ? 'Edit News Story' : 'Create News & Story Post' }}</h1>
    <p class="text-secondary">Publish an article or story to the front page and newsroom. English title and body are required.</p>
</div>

<form class="ajax-form" method="post" enctype="multipart/form-data" 
      action="{{ $item->exists ? route('admin.news.update', $item) : route('admin.news.store') }}">
    @csrf

    @if(request('from_blocks'))
        <input type="hidden" name="from_blocks" value="1">
    @endif

    <div class="panel p-4 mb-4">
        <h4 class="mb-3 text-dark fw-bold border-bottom pb-2">1. Publication Details</h4>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold" for="slug">URL Slug <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted small">/news/</span>
                    <input class="form-control" id="slug" name="slug" value="{{ $item->slug }}" required pattern="[A-Za-z0-9_-]+">
                </div>
                <div class="form-text small">Unique web address for this article. Letters, numbers, hyphens only.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold" for="category">Category <span class="text-danger">*</span></label>
                <input class="form-control" id="category" name="category" list="category-presets" value="{{ $item->category ?: 'Campus News' }}" required>
                <datalist id="category-presets">
                    <option value="STEM & Innovation">
                    <option value="Early Childhood">
                    <option value="Community Service">
                    <option value="Academic Excellence">
                    <option value="Safeguarding & Policy">
                    <option value="Campus News">
                    <option value="Student Life & Arts">
                </datalist>
                <div class="form-text small">Choose from list or type a custom category.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold" for="author">Author / Byline</label>
                <input class="form-control" id="author" name="author" value="{{ $item->author ?: 'GGS Editorial Desk' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold" for="read_time">Reading Time</label>
                <input class="form-control" id="read_time" name="read_time" value="{{ $item->read_time ?: '3 min read' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold" for="published_at">Publication Date</label>
                <input class="form-control" id="published_at" name="published_at" type="date" 
                       value="{{ $item->published_at ? $item->published_at->format('Y-m-d') : date('Y-m-d') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold" for="branch">Campus Branch (Optional)</label>
                <select class="form-select" name="branch_id" id="branch">
                    <option value="">All Campuses (General School News)</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @selected($item->branch_id === $branch->id)>
                            {{ $branch->name['en'] ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold" for="sort_order">Sort Order</label>
                <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ $item->sort_order ?? 0 }}" required>
                <div class="form-text small">Lower numbers appear first.</div>
            </div>
        </div>
    </div>

    <!-- MULTILINGUAL CONTENT -->
    <div class="panel p-4 mb-4">
        <h4 class="mb-3 text-dark fw-bold border-bottom pb-2">2. Article Content & Translations</h4>

        @foreach(['en' => 'English', 'km' => 'Khmer (ភាសាខ្មែរ)', 'zh' => 'Chinese (中文)'] as $locale => $label)
            <div class="translation mb-4 {{ $loop->first ? 'border-top-0 pt-0' : '' }}">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <h5 class="fw-bold mb-0 text-primary">{{ $label }}</h5>
                    @if($locale === 'en')
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Primary Required</span>
                    @else
                        <span class="badge bg-light text-secondary border">Optional Translation</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="title-{{ $locale }}">
                        Headline Title {{ $locale === 'en' ? '*' : '' }}
                    </label>
                    <input class="form-control {{ $locale === 'km' ? 'font-battambang' : '' }}" 
                           id="title-{{ $locale }}" 
                           name="title[{{ $locale }}]" 
                           value="{{ $item->title[$locale] ?? '' }}" 
                           @required($locale === 'en') 
                           maxlength="250">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="summary-{{ $locale }}">
                        Brief Summary / Excerpt
                    </label>
                    <textarea class="form-control {{ $locale === 'km' ? 'font-battambang' : '' }}" 
                              rows="2" 
                              id="summary-{{ $locale }}" 
                              name="summary[{{ $locale }}]" 
                              maxlength="800">{{ $item->summary[$locale] ?? '' }}</textarea>
                    <div class="form-text small">Short teaser shown on homepage news cards and spotlight banner.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="body-{{ $locale }}">
                        Full Article Body {{ $locale === 'en' ? '*' : '' }}
                    </label>
                    <textarea class="form-control {{ $locale === 'km' ? 'font-battambang' : '' }}" 
                              rows="6" 
                              id="body-{{ $locale }}" 
                              name="body[{{ $locale }}]" 
                              @required($locale === 'en')>{{ $item->body[$locale] ?? '' }}</textarea>
                </div>
            </div>
        @endforeach
    </div>

    <!-- COVER IMAGE & AUTO-GENERATION -->
    <div class="panel p-4 mb-4">
        <h4 class="mb-3 text-dark fw-bold border-bottom pb-2">3. Cover Media & Image</h4>

        <div class="mb-3">
            <label class="form-label fw-semibold" for="image">
                Article Photo <span class="text-secondary fw-normal">• JPG, PNG, WebP up to 5MB</span>
            </label>
            <div class="drop-area p-4 rounded-3 text-center border-dashed" id="drop-area" style="background: #fdfefe; border: 2px dashed #b5c7c7;">
                <input class="form-control" type="file" accept="image/jpeg,image/png,image/webp" id="image" name="image">
                <div class="small text-secondary mt-2">
                    <i class="fa-solid fa-cloud-arrow-up fa-2x mb-2 d-block text-muted"></i>
                    Upload custom photo or drag file here.
                </div>

                @php
                    $existingImg = ($item->exists && method_exists($item, 'imageUrl')) ? $item->imageUrl() : ($item->image ? asset('storage/' . $item->image) : null);
                @endphp

                <div class="mt-3 {{ $existingImg ? '' : 'd-none' }}" id="preview-container">
                    <img id="image-preview" 
                         class="image-preview mx-auto rounded-3 border shadow-sm" 
                         style="max-height: 220px; max-width: 100%; object-fit: contain;"
                         @if($existingImg) src="{{ $existingImg }}" @endif 
                         alt="Cover preview">
                    @if($existingImg)
                        <div class="form-check mt-3 d-inline-block text-start">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                            <label class="form-check-label small text-danger" for="remove_image">
                                <i class="fa-solid fa-trash me-1"></i> Remove current image (reverts to auto-generated SVG banner)
                            </label>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="alert alert-light border mt-3 mb-0 d-flex items-center gap-2 small text-secondary">
                <i class="fa-solid fa-wand-magic-sparkles text-warning fa-lg me-2"></i>
                <div>
                    <strong>Auto-Branding Generator:</strong> If no custom image is uploaded, our system will automatically generate a high-contrast, branded SVG banner featuring the GGS crest, category tag, and article headline!
                </div>
            </div>
        </div>
    </div>

    <!-- PUBLISHING OPTIONS -->
    <div class="panel p-4 mb-4">
        <h4 class="mb-3 text-dark fw-bold border-bottom pb-2">4. Visibility & Feature Status</h4>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 rounded-3 border bg-light">
                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" @checked($item->is_featured)>
                        <label class="form-check-label fw-bold text-dark" for="is_featured">
                            <i class="fa-solid fa-star text-warning me-1"></i> Feature as Spotlight Story
                        </label>
                    </div>
                    <div class="form-text small text-muted ps-4">
                        When enabled, this story is prominently showcased in the large Featured Publishing Spotlight block on the News page.
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 border bg-light">
                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" role="switch" id="published" name="published" value="1" @checked($item->published ?? true)>
                        <label class="form-check-label fw-bold text-dark" for="published">
                            <i class="fa-solid fa-globe text-success me-1"></i> Publish on Website
                        </label>
                    </div>
                    <div class="form-text small text-muted ps-4">
                        Make this story immediately visible on the homepage news section and public newsroom.
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="fa-solid fa-check me-1"></i> {{ $item->exists ? 'Update News Story' : 'Publish News Story' }}
            </button>
            <a href="{{ request('from_blocks') ? route('admin.blocks.index', ['section' => 'news']) : route('admin.news.index') }}" class="btn btn-outline-secondary px-3 py-2">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection
