@extends('admin.layout')

@section('title', 'Home Blocks')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1>Home Page Blocks</h1>
        <p class="text-secondary mb-0">Manage and update every frontend section of the landing page.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.blocks.create', ['section' => $selectedSection]) }}">
        + Add new block
    </a>
</div>

<!-- Section Filter Tabs -->
<div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
    <a href="{{ route('admin.blocks.index') }}" 
       class="btn btn-sm {{ empty($selectedSection) ? 'btn-dark' : 'btn-outline-secondary' }}">
        All Sections ({{ \App\Models\Block::count() }})
    </a>
    @foreach($sections as $secKey => $secName)
        @php
            $count = \App\Models\Block::where('section', $secKey)->count();
        @endphp
        <a href="{{ route('admin.blocks.index', ['section' => $secKey]) }}" 
           class="btn btn-sm {{ $selectedSection === $secKey ? 'btn-dark' : 'btn-outline-secondary' }}">
            {{ $secName }} ({{ $count }})
        </a>
    @endforeach
    <div class="vr mx-1 d-none d-md-block" style="height: 24px;"></div>
    <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm">
        <i class="fa-solid fa-newspaper me-1"></i> News &amp; Stories Menu ({{ \App\Models\News::count() }})
    </a>
</div>

@if($selectedSection === 'news')
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #0B2240 0%, #173863 100%); color: #fff;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-center" style="background: rgba(245, 166, 35, 0.15); border: 1px solid rgba(245, 166, 35, 0.4); color: #F5A623; font-size: 1.5rem;">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 text-white fw-bold">News &amp; Updates Layout Blocks</h4>
                        <p class="mb-0 text-white-50 small">
                            These blocks directly configure the layout of the <strong>Homepage News Section</strong> and the dedicated <strong>News & Publications Page (<a href="{{ route('news') }}" target="_blank" class="text-warning text-decoration-none">/news ↗</a>)</strong>.
                        </p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('news') }}" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Preview News Page
                    </a>
                    <a href="{{ route('admin.news.create', ['from_blocks' => 1]) }}" class="btn btn-sm btn-success fw-bold text-white">
                        <i class="fa-solid fa-plus me-1"></i> Create News Post
                    </a>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-warning text-dark fw-bold">
                        <i class="fa-solid fa-list-check me-1"></i> News &amp; Stories Menu
                    </a>
                </div>
            </div>

            <div class="row g-3 pt-3 border-top border-white-50">
                <div class="col-md-4">
                    <div class="p-3 rounded-2" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="fw-bold text-warning small mb-1">
                            <code>news_header</code>
                        </div>
                        <div class="small text-white">Controls the Newsroom hero banner, title, subtitle & intro description across both the homepage and /news.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-2" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="fw-bold text-warning small mb-1">
                            <code>news_spotlight</code>
                        </div>
                        <div class="small text-white">Controls the Featured Publishing Spotlight showcase banner with high-res cover image, author byline & read time.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-2" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="fw-bold text-warning small mb-1">
                            <code>news_announcement</code>
                        </div>
                        <div class="small text-white">Controls the Campus Bulletin / Announcement alert banner displayed at the top of the News Page.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th style="width: 140px;">Section</th>
                    <th>Key</th>
                    <th>Title & Subtitle</th>
                    <th style="width: 80px;">Icon / Media</th>
                    <th style="width: 80px;">Order</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blocks as $b)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $b->sectionLabel() }}
                            </span>
                        </td>
                        <td>
                            <code class="small text-primary">{{ $b->key }}</code>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $b->title['en'] ?? '' }}</div>
                            @if(!empty($b->title['km']))
                                <div class="small text-secondary">{{ $b->title['km'] }}</div>
                            @endif
                            @if(!empty($b->subtitle['en']))
                                <div class="badge bg-secondary-subtle text-secondary border mt-1">
                                    {{ $b->subtitle['en'] }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($b->image)
                                <img src="{{ str_starts_with($b->image, 'school/') ? asset('storage/'.$b->image) : $b->image }}" 
                                     alt="Preview" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;">
                            @elseif($b->icon)
                                <div class="rounded bg-light border d-flex align-items-center justify-center text-primary" 
                                     style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="{{ $b->icon }}"></i>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $b->sort_order }}</td>
                        <td>
                            @if($b->published)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Published</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border">Draft</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.blocks.edit', $b) }}">
                                Edit
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-item" 
                                    data-url="{{ route('admin.blocks.destroy', $b) }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-secondary">
                            No blocks found in this section. Click "+ Add new block" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $blocks->links() }}
    </div>
</div>

@if($selectedSection === 'news')
    <div class="panel mt-4 p-4 border rounded-3 shadow-sm bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark px-2.5 py-1 fw-bold">POSTS &amp; STORIES</span>
                    <h3 class="h4 mb-0 fw-bold">Front Page News &amp; Stories Posts</h3>
                </div>
                <p class="text-secondary small mb-0 mt-1">
                    These are all the published news stories, events, and updates that render dynamically on the homepage and the public newsroom.
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.news.create', ['from_blocks' => 1]) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> + Create News Story
                </a>
                <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-list me-1"></i> Full Newsroom Manager
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Cover</th>
                        <th>Story Headline</th>
                        <th>Category</th>
                        <th>Author &amp; Date</th>
                        <th style="width: 100px;">Spotlight</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 160px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsPosts as $np)
                        @php
                            $thumb = method_exists($np, 'imageUrl') ? $np->imageUrl() : ($np->image ? asset('storage/' . $np->image) : null);
                        @endphp
                        <tr>
                            <td>
                                @if($thumb)
                                    <img src="{{ $thumb }}" alt="Cover" class="rounded border shadow-sm object-fit-cover" style="width: 55px; height: 38px;">
                                @else
                                    <div class="rounded bg-light border text-muted d-flex align-items-center justify-content-center" style="width: 55px; height: 38px;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $np->title['en'] ?? 'Untitled' }}</div>
                                @if(!empty($np->title['km']))
                                    <div class="text-secondary small font-battambang">{{ $np->title['km'] }}</div>
                                @endif
                                <a href="{{ route('article', $np->slug) }}" target="_blank" class="small text-muted text-decoration-none">
                                    <code>/news/{{ $np->slug }}</code> <i class="fa-solid fa-arrow-up-right-from-square text-primary ms-1" style="font-size: 10px;"></i>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $np->category ?: 'Campus News' }}</span>
                            </td>
                            <td>
                                <div class="small fw-semibold">{{ $np->published_at ? $np->published_at->format('d M Y') : $np->created_at->format('d M Y') }}</div>
                                <div class="small text-muted">{{ $np->author ?: 'GGS Staff' }}</div>
                            </td>
                            <td>
                                @if($np->is_featured)
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle">
                                        <i class="fa-solid fa-star text-warning me-1"></i> Yes
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($np->published)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Published</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a class="btn btn-outline-primary" href="{{ route('admin.news.edit', [$np, 'from_blocks' => 1]) }}">
                                        Edit
                                    </a>
                                    <button class="btn btn-outline-danger delete-item" data-url="{{ route('admin.news.destroy', $np) }}">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">
                                No news stories posted yet. Click <strong>+ Create News Story</strong> above to post your first article.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

