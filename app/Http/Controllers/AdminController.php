<?php

namespace App\Http\Controllers;

use App\Models\{Block, Branch, Content, Message, News, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'blockCount' => Block::count(),
            'branchCount' => Branch::count(),
            'contentCount' => Content::count(),
            'newsCount' => News::count(),
            'messageCount' => Message::count(),
        ]);
    }

    public function index()
    {
        return view('admin.content.index');
    }

    public function data()
    {
        return DataTables::eloquent(Content::query()->with('branch'))
            ->editColumn('title', fn ($c) => $c->title['en'] ?? '')
            ->addColumn('branch_name', fn ($c) => $c->branch?->name['en'] ?? 'All branches')
            ->editColumn('published', fn ($c) => $c->published ? 'Published' : 'Draft')
            ->addColumn('actions', fn ($c) => '<a class="btn btn-sm btn-outline-primary" href="'.route('admin.content.edit', $c).'">Edit</a> <button class="btn btn-sm btn-outline-danger delete-item" data-url="'.route('admin.content.destroy', $c).'">Delete</button>')
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.content.form', ['item' => new Content, 'branches' => Branch::orderBy('id')->get()]);
    }

    public function edit(Content $content)
    {
        return view('admin.content.form', ['item' => $content, 'branches' => Branch::orderBy('id')->get()]);
    }

    private function image(Request $r, $model, array &$data)
    {
        if ($r->hasFile('image')) {
            $old = $model->image;
            $data['image'] = $r->file('image')->store('school', 'public');
            if ($old && str_starts_with($old, 'school/')) {
                Storage::disk('public')->delete($old);
            }
        } elseif ($r->boolean('remove_image')) {
            if ($model->image && str_starts_with($model->image, 'school/')) {
                Storage::disk('public')->delete($model->image);
            }
            $data['image'] = null;
        }
    }

    private function contentData(Request $r, ?Content $item = null)
    {
        $data = $r->validate([
            'type' => ['required', Rule::in(['hero', 'about', 'program', 'news', 'gallery'])],
            'slug' => ['required', 'alpha_dash:ascii', 'max:100', Rule::unique('contents')->ignore($item?->id)],
            'branch_id' => 'nullable|exists:branches,id',
            'title.en' => 'required|string|max:200',
            'title.km' => 'nullable|string|max:200',
            'title.zh' => 'nullable|string|max:200',
            'body.en' => 'nullable|string|max:12000',
            'body.km' => 'nullable|string|max:12000',
            'body.zh' => 'nullable|string|max:12000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order' => 'required|integer|min:0|max:100000',
        ]);
        $data['published'] = $r->boolean('published');
        $this->image($r, $item ?? new Content, $data);

        return $data;
    }

    public function store(Request $r)
    {
        Content::create($this->contentData($r));

        return response()->json(['message' => 'Content saved.', 'redirect' => route('admin.content.index')]);
    }

    public function update(Request $r, Content $content)
    {
        $content->update($this->contentData($r, $content));

        return response()->json(['message' => 'Content updated.', 'redirect' => route('admin.content.index')]);
    }

    public function destroy(Content $content)
    {
        if ($content->image && str_starts_with($content->image, 'school/')) {
            Storage::disk('public')->delete($content->image);
        }
        $content->delete();

        return response()->json(['message' => 'Deleted.']);
    }

    // --- HOME BLOCKS MANAGEMENT ---
    public function blocks(Request $r)
    {
        $query = Block::query();
        if ($section = $r->input('section')) {
            $query->where('section', $section);
        }
        $blocks = $query->orderBy('section')->orderBy('sort_order')->paginate(20)->withQueryString();

        $newsPosts = ($r->input('section') === 'news')
            ? News::with('branch')->orderBy('sort_order')->orderByDesc('published_at')->get()
            : collect();

        return view('admin.blocks.index', [
            'blocks' => $blocks,
            'sections' => Block::sections(),
            'selectedSection' => $r->input('section', ''),
            'newsPosts' => $newsPosts,
        ]);
    }

    public function blockCreate(Request $r)
    {
        $block = new Block([
            'section' => $r->input('section', 'hero'),
            'sort_order' => 0,
            'published' => true,
        ]);

        return view('admin.blocks.form', [
            'item' => $block,
            'sections' => Block::sections(),
        ]);
    }

    public function blockEdit(Block $block)
    {
        return view('admin.blocks.form', [
            'item' => $block,
            'sections' => Block::sections(),
        ]);
    }

    private function blockData(Request $r, ?Block $block = null): array
    {
        $data = $r->validate([
            'section' => ['required', 'string', 'max:50'],
            'key' => ['required', 'alpha_dash:ascii', 'max:100', Rule::unique('blocks')->ignore($block?->id)],
            'title.en' => 'required|string|max:300',
            'title.km' => 'nullable|string|max:300',
            'title.zh' => 'nullable|string|max:300',
            'subtitle.en' => 'nullable|string|max:300',
            'subtitle.km' => 'nullable|string|max:300',
            'subtitle.zh' => 'nullable|string|max:300',
            'body.en' => 'nullable|string|max:12000',
            'body.km' => 'nullable|string|max:12000',
            'body.zh' => 'nullable|string|max:12000',
            'icon' => 'nullable|string|max:100',
            'link_url' => 'nullable|string|max:500',
            'link_text.en' => 'nullable|string|max:100',
            'link_text.km' => 'nullable|string|max:100',
            'link_text.zh' => 'nullable|string|max:100',
            'extra_raw' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order' => 'required|integer|min:0|max:100000',
        ]);

        $data['published'] = $r->boolean('published');

        if (! empty($data['extra_raw'])) {
            $decoded = json_decode($data['extra_raw'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['extra'] = $decoded;
            }
        } elseif ($r->has('extra_raw') && empty($data['extra_raw'])) {
            $data['extra'] = null;
        }
        unset($data['extra_raw']);

        $this->image($r, $block ?? new Block, $data);

        return $data;
    }

    public function blockStore(Request $r)
    {
        Block::create($this->blockData($r));

        return response()->json(['message' => 'Block saved.', 'redirect' => route('admin.blocks.index')]);
    }

    public function blockUpdate(Request $r, Block $block)
    {
        $block->update($this->blockData($r, $block));

        return response()->json(['message' => 'Block updated.', 'redirect' => route('admin.blocks.index')]);
    }

    public function blockDestroy(Block $block)
    {
        if ($block->image && str_starts_with($block->image, 'school/')) {
            Storage::disk('public')->delete($block->image);
        }
        $block->delete();

        return response()->json(['message' => 'Block deleted.']);
    }

    // --- NEWS & STORIES MANAGEMENT ---
    public function newsIndex(Request $r)
    {
        $query = News::with('branch');

        if ($cat = $r->input('category')) {
            $query->where('category', $cat);
        }
        if ($search = $r->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title->en', 'like', "%{$search}%")
                  ->orWhere('title->km', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $news = $query->orderBy('sort_order')->orderByDesc('published_at')->paginate(12)->withQueryString();
        $categories = News::whereNotNull('category')->distinct()->pluck('category')->filter()->values();

        return view('admin.news.index', [
            'news' => $news,
            'categories' => $categories,
            'selectedCategory' => $r->input('category', ''),
            'searchQuery' => $r->input('q', ''),
            'totalCount' => News::count(),
            'publishedCount' => News::where('published', true)->count(),
            'featuredCount' => News::where('is_featured', true)->count(),
        ]);
    }

    public function newsCreate()
    {
        return view('admin.news.form', [
            'item' => new News([
                'category' => 'Campus News',
                'author' => auth()->user()?->name ?? 'GGS Editorial Desk',
                'read_time' => '3 min read',
                'published' => true,
                'published_at' => now(),
                'sort_order' => 0,
            ]),
            'branches' => Branch::orderBy('sort_order')->get(),
            'categories' => News::whereNotNull('category')->distinct()->pluck('category')->filter()->values(),
        ]);
    }

    public function newsEdit(News $news)
    {
        return view('admin.news.form', [
            'item' => $news,
            'branches' => Branch::orderBy('sort_order')->get(),
            'categories' => News::whereNotNull('category')->distinct()->pluck('category')->filter()->values(),
        ]);
    }

    private function newsData(Request $r, ?News $news = null): array
    {
        $data = $r->validate([
            'slug' => ['required', 'alpha_dash:ascii', 'max:120', Rule::unique('news')->ignore($news?->id)],
            'category' => ['required', 'string', 'max:100'],
            'author' => ['nullable', 'string', 'max:100'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'title.en' => 'required|string|max:300',
            'title.km' => 'nullable|string|max:300',
            'title.zh' => 'nullable|string|max:300',
            'summary.en' => 'nullable|string|max:1000',
            'summary.km' => 'nullable|string|max:1000',
            'summary.zh' => 'nullable|string|max:1000',
            'body.en' => 'required|string|max:20000',
            'body.km' => 'nullable|string|max:20000',
            'body.zh' => 'nullable|string|max:20000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_featured' => 'nullable|boolean',
            'published' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0|max:100000',
        ]);

        $data['is_featured'] = $r->boolean('is_featured');
        $data['published'] = $r->boolean('published');
        $data['published_at'] = !empty($data['published_at']) ? $data['published_at'] : now();

        // Handle uploaded image
        if ($r->hasFile('image')) {
            $old = $news?->image;
            $data['image'] = $r->file('image')->store('school', 'public');
            if ($old && str_starts_with($old, 'school/')) {
                Storage::disk('public')->delete($old);
            }
        } elseif ($r->boolean('remove_image')) {
            if ($news?->image && str_starts_with($news->image, 'school/')) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = null; // triggers auto-SVG generation
        }

        return $data;
    }

    public function newsStore(Request $r)
    {
        $data = $this->newsData($r);
        $news = News::create($data);

        // Keep mirror in Content table for backward compatibility
        Content::updateOrCreate(
            ['slug' => $news->slug],
            [
                'type' => 'news',
                'branch_id' => $news->branch_id,
                'title' => $news->title,
                'body' => $news->body,
                'image' => $news->image,
                'published' => $news->published,
                'sort_order' => $news->sort_order,
            ]
        );

        $targetUrl = $r->filled('from_blocks') ? route('admin.blocks.index', ['section' => 'news']) : route('admin.news.index');

        return response()->json([
            'message' => 'News story saved successfully! / បានរក្សាទុកព័ត៌មានដោយជោគជ័យ!',
            'redirect' => $targetUrl,
        ]);
    }

    public function newsUpdate(Request $r, News $news)
    {
        $data = $this->newsData($r, $news);
        $news->update($data);

        // Keep mirror in Content table
        Content::updateOrCreate(
            ['slug' => $news->slug],
            [
                'type' => 'news',
                'branch_id' => $news->branch_id,
                'title' => $news->title,
                'body' => $news->body,
                'image' => $news->image,
                'published' => $news->published,
                'sort_order' => $news->sort_order,
            ]
        );

        $targetUrl = $r->filled('from_blocks') ? route('admin.blocks.index', ['section' => 'news']) : route('admin.news.index');

        return response()->json([
            'message' => 'News story updated successfully! / បានកែប្រែព័ត៌មានដោយជោគជ័យ!',
            'redirect' => $targetUrl,
        ]);
    }

    public function newsDestroy(News $news)
    {
        if ($news->image && str_starts_with($news->image, 'school/')) {
            Storage::disk('public')->delete($news->image);
        }
        if ($news->image && str_starts_with($news->image, 'news/')) {
            Storage::disk('public')->delete($news->image);
        }

        Content::where('slug', $news->slug)->where('type', 'news')->delete();
        $news->delete();

        return response()->json(['message' => 'News story deleted.']);
    }

    // --- BRANCHES MANAGEMENT ---
    public function branches()
    {
        return view('admin.branches.index', ['branches' => Branch::orderBy('sort_order')->paginate(10)]);
    }

    public function branchCreate()
    {
        return view('admin.branches.form', ['item' => new Branch]);
    }

    public function branchEdit(Branch $branch)
    {
        return view('admin.branches.form', ['item' => $branch]);
    }

    private function branchData(Request $r, ?Branch $branch = null)
    {
        $data = $r->validate([
            'slug' => ['required', 'alpha_dash:ascii', 'max:100', Rule::unique('branches')->ignore($branch?->id)],
            'name.en' => 'required|string|max:200',
            'name.km' => 'nullable|string|max:200',
            'name.zh' => 'nullable|string|max:200',
            'address.en' => 'nullable|string|max:500',
            'address.km' => 'nullable|string|max:500',
            'address.zh' => 'nullable|string|max:500',
            'description.en' => 'nullable|string|max:5000',
            'description.km' => 'nullable|string|max:5000',
            'description.zh' => 'nullable|string|max:5000',
            'email' => 'nullable|email|max:190',
            'phone' => 'nullable|string|max:40',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order' => 'required|integer|min:0|max:100000',
        ]);
        $data['published'] = $r->boolean('published');
        $this->image($r, $branch ?? new Branch, $data);

        return $data;
    }

    public function branchStore(Request $r)
    {
        Branch::create($this->branchData($r));

        return response()->json(['message' => 'Branch saved.', 'redirect' => route('admin.branches.index')]);
    }

    public function branchUpdate(Request $r, Branch $branch)
    {
        $branch->update($this->branchData($r, $branch));

        return response()->json(['message' => 'Branch updated.', 'redirect' => route('admin.branches.index')]);
    }

    public function branchDestroy(Branch $branch)
    {
        abort_if(Content::where('branch_id', $branch->id)->exists() || Message::where('branch_id', $branch->id)->exists(), 422, 'This branch has content or messages. Reassign or remove them first.');
        if ($branch->image && str_starts_with($branch->image, 'school/')) {
            Storage::disk('public')->delete($branch->image);
        }
        $branch->delete();

        return response()->json(['message' => 'Branch deleted.']);
    }

    public function settings()
    {
        return view('admin.settings', ['settings' => Setting::all()->keyBy('key')]);
    }

    public function saveSettings(Request $r)
    {
        $data = $r->validate([
            'school_name.en' => 'required|string|max:200',
            'school_name.km' => 'nullable|string|max:200',
            'school_name.zh' => 'nullable|string|max:200',
            'tagline.en' => 'nullable|string|max:500',
            'tagline.km' => 'nullable|string|max:500',
            'tagline.zh' => 'nullable|string|max:500',
            'contact_email.en' => 'nullable|email|max:190',
            'contact_phone.en' => 'nullable|string|max:40',
        ]);
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json(['message' => 'Settings saved.']);
    }

    public function header()
    {
        $hero = Block::where('section', 'hero')->where('key', 'hero_main')->first();
        if (! $hero) {
            $hero = Block::where('section', 'hero')->first();
        }
        if (! $hero) {
            $hero = Block::create([
                'section' => 'hero',
                'key' => 'hero_main',
                'title' => [
                    'en' => 'A brighter future starts here: Transformative 21st Century Bilingual & Digital Education',
                    'km' => 'ការអប់រំប្រកបដោយគុណភាព និងការផ្លាស់ប្តូរឌីជីថល (អនាគតភ្លឺស្វាងចាប់ផ្តើមពីទីនេះ)',
                ],
                'subtitle' => [
                    'en' => 'Patriotic Heart, Global Mind',
                    'km' => 'បេះដូងស្នេហាជាតិ គំនិតកម្រិតសកល',
                ],
                'body' => [
                    'en' => 'Global Generation School (GGS) in Siem Reap equips Cambodian youth with rigorous academic excellence, fluent English, STEM innovation, AI literacy, life skills, and environmental sustainability.',
                    'km' => 'សាលាជំនាន់សកល (GGS) ក្រុងសៀមរាប ផ្តល់ជូននូវបទពិសោធន៍អប់រំឈានមុខគេ តាមរយៈការរួមបញ្ចូលគ្នារវាងកម្មវិធីសិក្សាជាតិ ភាសាអង់គ្លេស STEM បច្ចេកវិទ្យា AI និរន្តរភាពបរិស្ថាន និងបំណិនជីវិតពិតប្រាកដ។',
                ],
                'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2000&auto=format&fit=crop',
                'icon' => 'fa-solid fa-heart-pulse',
                'link_url' => '#pillars',
                'link_text' => [
                    'en' => 'Explore 6 Pillars',
                    'km' => 'ស្វែងយល់សសរស្តម្ភទាំង៦',
                ],
                'extra' => [
                    'highlights' => [
                        ['en' => 'MoEYS Recognized', 'km' => 'ទទួលស្គាល់ដោយ ក្រសួងអប់រំ'],
                        ['en' => 'Hands-on STEM & AI', 'km' => 'រៀន STEM & AI ជាក់ស្តែង'],
                        ['en' => '100% Child Safety', 'km' => 'សុវត្ថិភាពកុមារ១០០%'],
                    ],
                ],
                'published' => true,
                'sort_order' => 1,
            ]);
        }

        return view('admin.header', [
            'item' => $hero,
        ]);
    }

    public function saveHeader(Request $r)
    {
        $hero = Block::where('section', 'hero')->where('key', 'hero_main')->first();
        if (! $hero) {
            $hero = Block::where('section', 'hero')->first();
        }
        if (! $hero) {
            $hero = new Block(['section' => 'hero', 'key' => 'hero_main', 'sort_order' => 1]);
        }

        $data = $r->validate([
            'title.en' => 'required|string|max:300',
            'title.km' => 'nullable|string|max:300',
            'title.zh' => 'nullable|string|max:300',
            'subtitle.en' => 'nullable|string|max:300',
            'subtitle.km' => 'nullable|string|max:300',
            'subtitle.zh' => 'nullable|string|max:300',
            'body.en' => 'nullable|string|max:12000',
            'body.km' => 'nullable|string|max:12000',
            'body.zh' => 'nullable|string|max:12000',
            'icon' => 'nullable|string|max:100',
            'link_url' => 'nullable|string|max:500',
            'link_text.en' => 'nullable|string|max:100',
            'link_text.km' => 'nullable|string|max:100',
            'link_text.zh' => 'nullable|string|max:100',
            'image_url' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'cta_text.en' => 'nullable|string|max:100',
            'cta_text.km' => 'nullable|string|max:100',
            'highlights' => 'nullable|array',
        ]);

        $data['published'] = $r->boolean('published', true);

        // Process image upload or manual image URL
        $this->image($r, $hero, $data);
        if ($r->filled('image_url') && ! $r->hasFile('image')) {
            $data['image'] = $r->input('image_url');
        }
        unset($data['image_url']);

        // Structured extra data: highlights and cta_text
        $extra = is_array($hero->extra) ? $hero->extra : [];
        if ($r->has('highlights')) {
            $cleaned = [];
            foreach ((array) $r->input('highlights') as $hl) {
                if (! empty($hl['en']) || ! empty($hl['km'])) {
                    $cleaned[] = [
                        'en' => $hl['en'] ?? '',
                        'km' => $hl['km'] ?? ($hl['en'] ?? ''),
                    ];
                }
            }
            $extra['highlights'] = $cleaned;
        }
        if ($r->has('cta_text')) {
            $extra['cta_text'] = [
                'en' => $r->input('cta_text.en') ?: 'Admissions 2026-2027',
                'km' => $r->input('cta_text.km') ?: 'ចុះឈ្មោះចូលរៀន ២០២៦-២០២៧',
            ];
        }
        unset($data['highlights'], $data['cta_text']);
        $data['extra'] = $extra;

        if ($hero->exists) {
            $hero->update($data);
        } else {
            $hero->fill($data);
            $hero->save();
        }

        // Also sync Content with type='hero' if exists
        $contentHero = Content::where('type', 'hero')->first();
        if ($contentHero) {
            $contentHero->update([
                'title' => $hero->title,
                'body' => $hero->body,
                'image' => $hero->image,
            ]);
        }

        return response()->json([
            'message' => 'Hero section header updated successfully.',
            'redirect' => route('admin.header'),
        ]);
    }

    public function messages()
    {
        return view('admin.messages', ['messages' => Message::with('branch')->latest()->paginate(15)]);
    }

    public function messageDestroy(Message $message)
    {
        $message->delete();

        return response()->json(['message' => 'Message deleted.']);
    }
}
