<?php

namespace App\Http\Controllers;

use App\Models\{Block, Branch, Content, Message, News};
use Illuminate\Http\Request;

class SiteController extends Controller
{
    private function base(): array
    {
        return ['branches' => Branch::where('published', true)->orderBy('sort_order')->get()];
    }

    private function items(string $type)
    {
        return Content::where('published', true)->where('type', $type)->where(function ($q) {
            $q->whereNull('branch_id')->orWhereHas('branch', fn ($b) => $b->where('published', true));
        })->orderBy('sort_order')->orderByDesc('id');
    }

    public function home()
    {
        $blocks = Block::where('published', true)->orderBy('sort_order')->get()->groupBy('section');

        $latestNews = News::published()->orderBy('sort_order')->orderByDesc('published_at')->limit(3)->get();
        if ($latestNews->isEmpty()) {
            $latestNews = $this->items('news')->limit(3)->get();
        }

        return view('site.home', $this->base() + [
            'blocks' => $blocks,
            'hero' => $this->items('hero')->first(),
            'about' => $this->items('about')->first(),
            'programs' => $this->items('program')->limit(6)->get(),
            'posts' => $latestNews,
            'gallery' => $this->items('gallery')->limit(6)->get(),
        ]);
    }

    public function news(Request $request)
    {
        $newsBlocks = Block::where('published', true)->where('section', 'news')->orderBy('sort_order')->get()->keyBy('key');
        $category = $request->input('category');
        $search = $request->input('q');

        $newsQuery = News::published();
        if (!empty($category) && $category !== 'all') {
            $newsQuery->where('category', $category);
        }
        if (!empty($search)) {
            $newsQuery->where(function ($q) use ($search) {
                $q->where('title->en', 'like', "%{$search}%")
                  ->orWhere('title->km', 'like', "%{$search}%")
                  ->orWhere('body->en', 'like', "%{$search}%")
                  ->orWhere('body->km', 'like', "%{$search}%");
            });
        }

        $featured = News::published()->featured()->first() ?? News::published()->orderBy('sort_order')->first();

        if ($newsQuery->exists() || News::published()->exists()) {
            $posts = $newsQuery->orderBy('sort_order')->orderByDesc('published_at')->paginate(6)->withQueryString();
        } else {
            // Fallback to Content model for backward compatibility / tests
            $contentQuery = $this->items('news');
            if (!empty($search)) {
                $contentQuery->where(function ($q) use ($search) {
                    $q->where('title->en', 'like', "%{$search}%")
                      ->orWhere('title->km', 'like', "%{$search}%");
                });
            }
            $posts = $contentQuery->paginate(6)->withQueryString();
        }

        $categories = News::published()->whereNotNull('category')->distinct()->pluck('category')->filter()->values();

        return view('site.news', $this->base() + [
            'posts' => $posts,
            'featured' => $featured,
            'newsBlocks' => $newsBlocks,
            'categories' => $categories,
            'selectedCategory' => $category ?: 'all',
            'searchQuery' => $search,
        ]);
    }

    public function article(string $slug)
    {
        // 1. Resolve from News table first
        $post = News::where('slug', $slug)->first();
        if ($post) {
            abort_unless($post->published && (! $post->branch_id || $post->branch?->published), 404);
            $related = News::published()->where('id', '!=', $post->id)->latest('published_at')->limit(3)->get();

            return view('site.article', $this->base() + [
                'post' => $post,
                'related' => $related,
            ]);
        }

        // 2. Fallback to Content table
        $content = Content::where('slug', $slug)->first();
        if ($content) {
            abort_unless($content->type === 'news' && $content->published && (! $content->branch_id || $content->branch?->published), 404);
            $related = News::published()->latest('published_at')->limit(3)->get();

            return view('site.article', $this->base() + [
                'post' => $content,
                'related' => $related,
            ]);
        }

        abort(404);
    }

    public function branch(Branch $branch)
    {
        abort_unless($branch->published, 404);

        return view('site.branch', $this->base() + [
            'branch' => $branch,
            'programs' => $this->items('program')->where('branch_id', $branch->id)->get(),
            'posts' => $this->items('news')->where('branch_id', $branch->id)->paginate(6)->withQueryString(),
        ]);
    }

    public function language(Request $r, string $locale)
    {
        abort_unless(in_array($locale, ['en', 'km', 'zh']), 404);
        $r->session()->put('locale', $locale);

        return response()->json(['ok' => true]);
    }

    public function contact(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:190',
            'phone' => 'nullable|string|max:40',
            'message' => 'required|string|max:3000',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if (! empty($data['branch_id']) && ! Branch::whereKey($data['branch_id'])->where('published', true)->exists()) {
            abort(422);
        }

        Message::create($data);

        return response()->json(['message' => __('site.sent')]);
    }

    public function postNews(Request $r)
    {
        // 1. Check or perform staff authentication
        if (! auth()->check()) {
            $r->validate([
                'staff_email' => 'required|email',
                'staff_password' => 'required|string',
            ], [
                'staff_email.required' => 'Please provide staff email to post news.',
                'staff_password.required' => 'Please provide staff password to post news.',
            ]);

            if (! auth()->attempt(['email' => $r->staff_email, 'password' => $r->staff_password])) {
                return response()->json([
                    'message' => 'Invalid staff credentials. Only school staff can post news.',
                    'errors' => ['staff_password' => ['Invalid staff email or password.']],
                ], 422);
            }
        }

        // 2. Validate news content
        $data = $r->validate([
            'title.en' => 'required|string|max:200',
            'title.km' => 'nullable|string|max:200',
            'title.zh' => 'nullable|string|max:200',
            'body.en' => 'required|string|max:12000',
            'body.km' => 'nullable|string|max:12000',
            'body.zh' => 'nullable|string|max:12000',
            'branch_id' => 'nullable|exists:branches,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'title.en.required' => 'English title is required.',
            'body.en.required' => 'Article body content is required.',
        ]);

        // 3. Generate unique slug
        $baseSlug = \Illuminate\Support\Str::slug($data['title']['en']);
        if (empty($baseSlug)) {
            $baseSlug = 'news-' . uniqid();
        }
        $slug = $baseSlug;
        $counter = 1;
        while (Content::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // 4. Handle image upload if provided
        $imagePath = null;
        if ($r->hasFile('image')) {
            $imagePath = $r->file('image')->store('school', 'public');
        }

        // 5. Fill bilingual fallbacks if Khmer or English is omitted
        $titles = [
            'en' => $data['title']['en'],
            'km' => !empty($data['title']['km']) ? $data['title']['km'] : $data['title']['en'],
            'zh' => !empty($data['title']['zh']) ? $data['title']['zh'] : null,
        ];
        $bodies = [
            'en' => $data['body']['en'],
            'km' => !empty($data['body']['km']) ? $data['body']['km'] : $data['body']['en'],
            'zh' => !empty($data['body']['zh']) ? $data['body']['zh'] : null,
        ];

        // 6. Create Content entry (for backward compatibility)
        $content = Content::create([
            'type' => 'news',
            'slug' => $slug,
            'branch_id' => !empty($data['branch_id']) ? $data['branch_id'] : null,
            'title' => $titles,
            'body' => $bodies,
            'image' => $imagePath,
            'published' => true,
            'sort_order' => 0,
        ]);

        // 7. Create News table entry with auto-SVG fallback
        News::create([
            'slug' => $slug,
            'branch_id' => !empty($data['branch_id']) ? $data['branch_id'] : null,
            'category' => 'Campus News',
            'title' => $titles,
            'body' => $bodies,
            'summary' => [
                'en' => \Illuminate\Support\Str::limit(strip_tags($bodies['en']), 160),
                'km' => \Illuminate\Support\Str::limit(strip_tags($bodies['km']), 160),
            ],
            'image' => $imagePath,
            'author' => auth()->user()?->name ?? 'GGS Editorial Desk',
            'read_time' => '3 min read',
            'published' => true,
            'published_at' => now(),
            'sort_order' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'News posted successfully! / បានចុះផ្សាយព័ត៌មានដោយជោគជ័យ!',
            'redirect' => route('article', $content->slug),
        ]);
    }
}
