<?php

namespace Tests\Feature;

use App\Models\{Block, News, Role, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsPublishingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'super-admin']);

        return User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.test',
            'password' => 'secret-password-1234',
            'role_id' => $role->id,
        ]);
    }

    public function test_news_page_renders_with_spotlight_and_auto_generated_image(): void
    {
        Storage::fake('public');

        // Create a news article with NO uploaded image
        $news = News::create([
            'slug' => 'stem-robotics-award-2026',
            'category' => 'STEM & Innovation',
            'title' => [
                'en' => 'National Robotics Championship Gold Winner',
                'km' => 'ជ័យលាភីមាសផ្នែករ៉ូបូតថ្នាក់ជាតិ',
            ],
            'summary' => [
                'en' => 'Our students won first prize at the national showcase.',
                'km' => 'សិស្សានុសិស្សយើងទទួលបានជ័យលាភីលេខ១។',
            ],
            'body' => [
                'en' => 'Full article detailing our robotics program and students achievement.',
                'km' => 'ព័ត៌មានលម្អិតអំពីកម្មវិធីរ៉ូបូត។',
            ],
            'image' => null, // triggers auto-SVG generation
            'author' => 'Dr. Wood',
            'read_time' => '4 min read',
            'is_featured' => true,
            'published' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        // Assert SVG file was generated and saved to storage
        $this->assertNotEmpty($news->image);
        Storage::disk('public')->assertExists($news->image);

        // Visit public news page
        $response = $this->get('/news');
        $response->assertOk();
        $response->assertSee('National Robotics Championship Gold Winner');
        $response->assertSee('STEM & Innovation');
        $response->assertSee('Dr. Wood');

        // Verify that NO "+ Post News" button is rendered on the public news page
        $response->assertDontSee('+ Post News');
        $response->assertDontSee('Post News');

        // Visit the single article page
        $articleResp = $this->get('/news/' . $news->slug);
        $articleResp->assertOk();
        $articleResp->assertSee('National Robotics Championship Gold Winner');
        $articleResp->assertSee('Full article detailing our robotics program');
    }

    public function test_news_category_filter_and_search(): void
    {
        Storage::fake('public');

        News::create([
            'slug' => 'stem-story',
            'category' => 'STEM',
            'title' => ['en' => 'AI and Robotics Workshop'],
            'body' => ['en' => 'Workshop details.'],
            'published' => true,
        ]);

        News::create([
            'slug' => 'arts-story',
            'category' => 'Arts',
            'title' => ['en' => 'Traditional Khmer Music Festival'],
            'body' => ['en' => 'Music details.'],
            'published' => true,
        ]);

        // Category filter
        $this->get('/news?category=STEM')
            ->assertOk()
            ->assertSee('AI and Robotics Workshop')
            ->assertDontSee('Traditional Khmer Music Festival');

        // Search query
        $this->get('/news?q=Festival')
            ->assertOk()
            ->assertSee('Traditional Khmer Music Festival')
            ->assertDontSee('AI and Robotics Workshop');
    }

    public function test_admin_can_view_news_blocks_in_home_blocks_management(): void
    {
        $admin = $this->admin();

        Block::create([
            'section' => 'news',
            'key' => 'news_header',
            'title' => ['en' => 'School News & Stories'],
            'sort_order' => 1,
            'published' => true,
        ]);

        Block::create([
            'section' => 'news',
            'key' => 'news_spotlight',
            'title' => ['en' => 'Annual STEM Showcase 2026'],
            'sort_order' => 2,
            'published' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/blocks?section=news')
            ->assertOk()
            ->assertSee('Layout Blocks')
            ->assertSee('news_header')
            ->assertSee('news_spotlight');
    }

    public function test_admin_can_manage_news_stories_crud(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        // 1. Admin can view News index and create form
        $this->actingAs($admin)
            ->get('/admin/news')
            ->assertOk()
            ->assertSee('News & Stories');

        $this->actingAs($admin)
            ->get('/admin/news/create')
            ->assertOk()
            ->assertSee('Create News & Story Post');

        // 2. Admin can store a new News story
        $postData = [
            'slug' => 'science-fair-2026',
            'category' => 'STEM & Innovation',
            'author' => 'Mr. Sokha',
            'read_time' => '5 min read',
            'published_at' => now()->format('Y-m-d'),
            'title' => [
                'en' => 'Annual Science & Tech Fair 2026',
                'km' => 'ពិព័រណ៍វិទ្យាសាស្ត្រ និងបច្ចេកវិទ្យា ២០២៦',
            ],
            'summary' => [
                'en' => 'Amazing student discoveries.',
                'km' => 'ការរកឃើញថ្មីៗរបស់សិស្ស។',
            ],
            'body' => [
                'en' => 'Comprehensive science fair coverage with many experiments.',
                'km' => 'ការចុះផ្សាយលម្អិតអំពីពិព័រណ៍វិទ្យាសាស្ត្រ។',
            ],
            'is_featured' => 1,
            'published' => 1,
            'sort_order' => 1,
            'from_blocks' => 1,
        ];

        $storeResp = $this->actingAs($admin)->postJson('/admin/news', $postData);
        $storeResp->assertOk();
        $this->assertDatabaseHas('news', ['slug' => 'science-fair-2026']);

        $news = News::where('slug', 'science-fair-2026')->firstOrFail();
        $this->assertEquals('Annual Science & Tech Fair 2026', $news->title['en']);
        $this->assertTrue($news->is_featured);

        // Verify it appears in /admin/blocks?section=news
        $this->actingAs($admin)
            ->get('/admin/blocks?section=news')
            ->assertOk()
            ->assertSee('Annual Science & Tech Fair 2026');

        // 3. Admin can edit form and update the news story
        $this->actingAs($admin)
            ->get('/admin/news/' . $news->id . '/edit')
            ->assertOk()
            ->assertSee('Annual Science & Tech Fair 2026');

        $updateData = $postData;
        $updateData['title']['en'] = 'Updated Science & Tech Fair 2026';
        $updateResp = $this->actingAs($admin)->postJson('/admin/news/' . $news->id, $updateData);
        $updateResp->assertOk();

        $news->refresh();
        $this->assertEquals('Updated Science & Tech Fair 2026', $news->title['en']);

        // Check update reflected on public website
        $this->get('/news/' . $news->slug)
            ->assertOk()
            ->assertSee('Updated Science & Tech Fair 2026');

        // 4. Admin can delete the news story
        $deleteResp = $this->actingAs($admin)->deleteJson('/admin/news/' . $news->id);
        $deleteResp->assertOk();
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }
}
