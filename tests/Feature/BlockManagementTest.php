<?php

namespace Tests\Feature;

use App\Models\{Block, Permission, Role, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlockManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'super-admin']);

        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'test-password-1234',
            'role_id' => $role->id,
        ]);
    }

    public function test_admin_can_view_blocks_index_and_create_form(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/blocks')
            ->assertOk()
            ->assertSee('Home Page Blocks');

        $this->actingAs($admin)
            ->get('/admin/blocks/create?section=pillars')
            ->assertOk()
            ->assertSee('Create New Block');
    }

    public function test_admin_can_create_update_and_delete_block(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        // 1. Create a block
        $response = $this->actingAs($admin)->postJson('/admin/blocks', [
            'section' => 'pillars',
            'key' => 'pillar_robotics',
            'title' => ['en' => 'Robotics & Automation', 'km' => 'រ៉ូបូត និងស្វ័យប្រវត្តិកម្ម'],
            'subtitle' => ['en' => 'Future Tech', 'km' => 'បច្ចេកវិទ្យាអនាគត'],
            'body' => ['en' => 'Learn robotics hands-on.', 'km' => 'រៀនរ៉ូបូតជាក់ស្តែង។'],
            'icon' => 'fa-solid fa-robot',
            'sort_order' => 10,
            'published' => 1,
            'image' => UploadedFile::fake()->image('robot.jpg'),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('blocks', ['key' => 'pillar_robotics']);

        $block = Block::where('key', 'pillar_robotics')->firstOrFail();
        $this->assertEquals('Robotics & Automation', $block->title['en']);
        Storage::disk('public')->assertExists($block->image);

        // 2. View on public home page
        $this->get('/')->assertOk()->assertSee('Robotics & Automation');

        // 3. Update the block
        $updateResp = $this->actingAs($admin)->postJson('/admin/blocks/' . $block->id, [
            'section' => 'pillars',
            'key' => 'pillar_robotics',
            'title' => ['en' => 'Advanced Robotics & AI', 'km' => 'រ៉ូបូត និង AI កម្រិតខ្ពស់'],
            'sort_order' => 10,
            'published' => 1,
        ]);

        $updateResp->assertOk();
        $block->refresh();
        $this->assertEquals('Advanced Robotics & AI', $block->title['en']);

        // Check update reflected on home page
        $this->get('/')->assertOk()->assertSee('Advanced Robotics & AI');

        // 4. Delete the block
        $deleteResp = $this->actingAs($admin)->deleteJson('/admin/blocks/' . $block->id);
        $deleteResp->assertOk();
        $this->assertDatabaseMissing('blocks', ['id' => $block->id]);
    }

    public function test_guest_and_unauthorized_user_cannot_manage_blocks(): void
    {
        $this->get('/admin/blocks')->assertRedirect('/login');

        $role = Role::create(['name' => 'Staff', 'slug' => 'staff']);
        $user = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@example.test',
            'password' => 'test-password-1234',
            'role_id' => $role->id,
        ]);

        $this->actingAs($user)->get('/admin/blocks')->assertForbidden();
        $this->actingAs($user)->postJson('/admin/blocks', [])->assertForbidden();
    }

    public function test_news_menu_in_backend_blocks_and_frontend_news_posting(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        // 1. Verify 'news' is listed in Block::sections()
        $this->assertArrayHasKey('news', Block::sections());

        // 2. Admin can create a news block in backend blocks
        $resp = $this->actingAs($admin)->postJson('/admin/blocks', [
            'section' => 'news',
            'key' => 'news_header',
            'title' => ['en' => 'School News & Stories', 'km' => 'ព្រឹត្តិការណ៍ថ្មីៗរបស់សាលា'],
            'subtitle' => ['en' => 'Latest News & Updates', 'km' => 'ព័ត៌មាន & សកម្មភាព'],
            'body' => ['en' => 'All campus activities.', 'km' => 'សកម្មភាពសាលាទាំងអស់'],
            'icon' => 'fa-solid fa-newspaper',
            'sort_order' => 1,
            'published' => 1,
        ]);
        $resp->assertOk();
        $this->assertDatabaseHas('blocks', ['section' => 'news', 'key' => 'news_header']);

        // Check backend filter tab works
        $this->actingAs($admin)->get('/admin/blocks?section=news')
            ->assertOk()
            ->assertSee('School News & Stories');

        // 3. Frontend news posting by authenticated staff
        $postResp = $this->actingAs($admin)->postJson('/news/post', [
            'title' => ['en' => 'Campus Grand Opening Ceremony', 'km' => 'ពិធីសម្ពោធសាលារៀន'],
            'body' => ['en' => 'Today we celebrated our grand opening with community leaders.', 'km' => 'ថ្ងៃនេះយើងបានប្រារព្ធពិធីសម្ពោធ...'],
            'image' => UploadedFile::fake()->image('ceremony.jpg'),
        ]);

        $postResp->assertOk();
        $this->assertDatabaseHas('contents', ['type' => 'news', 'slug' => 'campus-grand-opening-ceremony']);
        $this->get('/news/campus-grand-opening-ceremony')->assertOk()->assertSee('Campus Grand Opening Ceremony');

        // 4. Frontend news posting by unauthenticated guest using staff credentials
        $this->post('/logout'); // logout admin
        $this->assertGuest();

        $guestPostResp = $this->postJson('/news/post', [
            'staff_email' => 'admin@example.test',
            'staff_password' => 'test-password-1234',
            'title' => ['en' => 'Science Fair Winners Announced', 'km' => 'ជយលាភីវិទ្យាសាស្ត្រ'],
            'body' => ['en' => 'Congratulations to all students.', 'km' => 'សូមអបអរសាទរសិស្សានុសិស្សទាំងអស់'],
        ]);

        $guestPostResp->assertOk();
        $this->assertDatabaseHas('contents', ['type' => 'news', 'slug' => 'science-fair-winners-announced']);
        $this->get('/news/science-fair-winners-announced')->assertOk()->assertSee('Science Fair Winners Announced');
    }
}

