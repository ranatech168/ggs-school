@extends('admin.layout')

@section('title', 'News & Stories')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">News & Stories</h1>
        <p class="text-secondary mb-0">Create, edit, and manage all news publications displayed on the front page and newsroom.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('admin.blocks.index', ['section' => 'news']) }}">
            <i class="fa-solid fa-shapes me-1"></i> Home News Blocks
        </a>
        <a class="btn btn-outline-primary" href="{{ route('news') }}" target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Newsroom
        </a>
        <a class="btn btn-primary" href="{{ route('admin.news.create') }}">
            <i class="fa-solid fa-plus me-1"></i> Add News Story
        </a>
    </div>
</div>

<!-- STATS ROW -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="stat p-3 rounded-3 shadow-sm bg-white border">
            <span class="text-secondary small fw-bold text-uppercase">Total Stories</span>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <strong class="h2 mb-0">{{ $totalCount }}</strong>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5">
                    <i class="fa-solid fa-newspaper me-1"></i> All
                </span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="stat p-3 rounded-3 shadow-sm bg-white border">
            <span class="text-secondary small fw-bold text-uppercase">Featured Spotlights</span>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <strong class="h2 mb-0 text-warning">{{ $featuredCount }}</strong>
                <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1.5">
                    <i class="fa-solid fa-star me-1 text-warning"></i> Spotlight
                </span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="stat p-3 rounded-3 shadow-sm bg-white border">
            <span class="text-secondary small fw-bold text-uppercase">Published on Website</span>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <strong class="h2 mb-0 text-success">{{ $publishedCount }}</strong>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5">
                    <i class="fa-solid fa-circle-check me-1"></i> Active
                </span>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER TOOLBAR -->
<div class="panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.news.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary border-end-0">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" 
                       name="q" 
                       value="{{ $searchQuery }}" 
                       class="form-control border-start-0" 
                       placeholder="Search by title, keywords or slug...">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories ({{ $totalCount }})</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100">
                Filter
            </button>
            @if($searchQuery || $selectedCategory)
                <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- NEWS TABLE -->
<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">Cover</th>
                    <th>Story Title & Slug</th>
                    <th>Category</th>
                    <th>Author & Date</th>
                    <th style="width: 100px;">Featured</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 160px;" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    @php
                        $thumb = method_exists($item, 'imageUrl') ? $item->imageUrl() : ($item->image ? asset('storage/' . $item->image) : null);
                        $isAuto = empty($item->image) || str_contains($item->image, 'auto-');
                    @endphp
                    <tr>
                        <td>
                            @if($thumb)
                                <img src="{{ $thumb }}" 
                                     alt="Thumb" 
                                     class="rounded-2 border shadow-sm object-fit-cover" 
                                     style="width: 60px; height: 42px;">
                            @else
                                <div class="rounded-2 bg-light border text-muted d-flex align-items-center justify-content-center" style="width: 60px; height: 42px;">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-0.5">
                                {{ $item->title['en'] ?? 'Untitled' }}
                            </div>
                            @if(!empty($item->title['km']))
                                <div class="text-secondary small font-battambang mb-1">
                                    {{ $item->title['km'] }}
                                </div>
                            @endif
                            <div class="small">
                                <code class="text-muted">/news/{{ $item->slug }}</code>
                                <a href="{{ route('article', $item->slug) }}" target="_blank" class="text-decoration-none text-primary ms-1" title="View live page">
                                    <i class="fa-solid fa-arrow-up-right-from-square small"></i>
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->category ?: 'Campus News' }}
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">
                                <i class="fa-regular fa-calendar text-muted me-1"></i>
                                {{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}
                            </div>
                            <div class="small text-muted">
                                <i class="fa-solid fa-feather me-1"></i>
                                {{ $item->author ?: 'GGS Staff' }}
                            </div>
                        </td>
                        <td>
                            @if($item->is_featured)
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle">
                                    <i class="fa-solid fa-star text-warning me-1"></i> Spotlight
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($item->published)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    Published
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-outline-primary" href="{{ route('admin.news.edit', $item) }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <button class="btn btn-outline-danger delete-item" 
                                        data-url="{{ route('admin.news.destroy', $item) }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-regular fa-newspaper fa-2x mb-2 d-block text-muted"></i>
                            No news stories found. Click <strong>+ Add News Story</strong> to publish your first front page article.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($news->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $news->links() }}
        </div>
    @endif
</div>
@endsection

