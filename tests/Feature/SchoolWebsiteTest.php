<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\{User,Role,Permission,Branch,Content,Message,Block};

class SchoolWebsiteTest extends TestCase {
 use RefreshDatabase;
 
 private function admin(): User {
  $role = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Admin']);
  return User::firstOrCreate(['email' => 'admin@example.test'], [
      'name' => 'Admin',
      'password' => 'test-password-1234',
      'role_id' => $role->id,
  ]);
 }

 public function test_home_and_locale_switch():void {
  $this->seed();
  $this->get('/')->assertOk()->assertSee('A brighter future');
  $this->post('/language/km')->assertOk();
  $this->get('/')->assertSee('អនាគត');
  $this->post('/language/invalid')->assertNotFound();
 }

 public function test_public_ignores_drafts_and_unpublished_branches():void {
  $b=Branch::create(['slug'=>'private','name'=>['en'=>'Private branch'],'published'=>false]);
  Content::create(['slug'=>'hidden-post','type'=>'news','title'=>['en'=>'Secret title'],'branch_id'=>$b->id,'published'=>true]);
  $this->get('/branches/private')->assertNotFound();
  $this->get('/news/hidden-post')->assertNotFound();
  $this->get('/news')->assertDontSee('Secret title');
 }

 public function test_contact_and_public_validation():void {
  $this->postJson('/contact',['name'=>'Test','email'=>'bad','message'=>'Hello'])->assertUnprocessable();
  $this->postJson('/contact',['name'=>'Test','email'=>'test@example.test','message'=>'Hello'])->assertOk();
  $this->assertDatabaseHas('messages',['email'=>'test@example.test']);
 }

 public function test_permissions_are_enforced_on_server():void {
  $role=Role::create(['name'=>'Reader','slug'=>'reader']);
  $u=User::create(['name'=>'Reader','email'=>'reader@example.test','password'=>'test-password-1234','role_id'=>$role->id]);
  $this->get('/admin/content')->assertRedirect('/login');
  $this->actingAs($u)->get('/admin/content')->assertForbidden();
  $this->actingAs($u)->post('/admin/users',[])->assertForbidden();
 }

 public function test_content_crud_and_image_upload():void {
  Storage::fake('public');
  $user=$this->admin();
  $this->actingAs($user)->postJson('/admin/content',['type'=>'news','slug'=>'our-news','title'=>['en'=>'A story'],'body'=>['en'=>'Hello'],'sort_order'=>0,'published'=>1,'image'=>UploadedFile::fake()->image('story.jpg')])->assertOk();
  $content=Content::where('slug','our-news')->firstOrFail();
  Storage::disk('public')->assertExists($content->image);
  $this->get('/news/our-news')->assertSee('A story');
  $this->actingAs($user)->deleteJson('/admin/content/'.$content->id)->assertOk();
  $this->assertDatabaseMissing('contents',['id'=>$content->id]);
 }

 public function test_branch_with_related_content_cannot_be_deleted():void {
  $admin=$this->admin();
  $b=Branch::create(['slug'=>'main','name'=>['en'=>'Main'],'published'=>true]);
  Content::create(['slug'=>'branch-news','type'=>'news','title'=>['en'=>'News'],'branch_id'=>$b->id,'published'=>true]);
  $this->actingAs($admin)->deleteJson('/admin/branches/'.$b->id)->assertStatus(422);
  $this->assertDatabaseHas('branches',['id'=>$b->id]);
 }

 public function test_super_admin_role_cannot_be_edited():void {
  $admin=$this->admin();
  $this->actingAs($admin)->postJson('/admin/roles',['id'=>$admin->role_id,'name'=>'x','slug'=>'x'])->assertForbidden();
 }

 public function test_admin_pages_render():void {
  $admin=$this->admin();
  foreach(['/admin','/admin/header','/admin/content','/admin/content/create','/admin/branches','/admin/branches/create','/admin/settings','/admin/messages','/admin/users','/admin/roles','/admin/blocks','/admin/news'] as $url) {
   $this->actingAs($admin)->get($url)->assertOk();
  }
 }

 public function test_admin_can_edit_entities_and_settings():void {
  $admin=$this->admin();
  $b=Branch::create(['slug'=>'main','name'=>['en'=>'Main'],'published'=>true]);
  $c=Content::create(['slug'=>'news-1','type'=>'news','title'=>['en'=>'News'],'branch_id'=>$b->id,'published'=>true]);
  $this->actingAs($admin)->get('/admin/branches/'.$b->id.'/edit')->assertOk()->assertSee('Main');
  $this->get('/admin/content/'.$c->id.'/edit')->assertOk()->assertSee('News');
  $this->postJson('/admin/settings',['school_name'=>['en'=>'New School'],'tagline'=>['en'=>'Welcome'],'contact_email'=>['en'=>'office@example.test'],'contact_phone'=>['en'=>'123']])->assertOk();
  $this->get('/')->assertSee('New School');
  $this->postJson('/admin/branches/'.$b->id,['slug'=>'main','name'=>['en'=>'Main Updated'],'sort_order'=>0,'published'=>1])->assertOk();
  $this->get('/branches/main')->assertSee('Main Updated');
  $this->postJson('/admin/content/'.$c->id,['slug'=>'news-1','type'=>'news','title'=>['en'=>'News Updated'],'sort_order'=>0,'branch_id'=>$b->id,'published'=>1])->assertOk();
  $this->get('/news/news-1')->assertSee('News Updated');
 }

 public function test_manage_access_can_create_roles_and_users():void {
  $admin=$this->admin();
  $permission=Permission::firstOrCreate(['slug'=>'manage-content'], ['name'=>'Manage content']);
  $this->actingAs($admin)->postJson('/admin/roles',['name'=>'Content Editor','slug'=>'content-editor','permissions'=>[$permission->id]])->assertOk();
  $role=Role::where('slug','content-editor')->firstOrFail();
  $this->postJson('/admin/users',['name'=>'Editor','email'=>'editor@example.test','password'=>'strong-password-123','role_id'=>$role->id])->assertOk();
  $editor=User::where('email','editor@example.test')->firstOrFail();
  $this->actingAs($editor)->get('/admin/content')->assertOk();
  $this->get('/admin/users')->assertForbidden();
 }

 public function test_frontend_login_button_and_dashboard_redirect():void {
  $this->get('/')->assertOk()->assertSee('Login')->assertSee(route('login'));
  $admin=$this->admin();
  $this->actingAs($admin)->get('/')->assertOk()->assertSee('Dashboard')->assertSee(route('admin.dashboard'));
  $this->actingAs($admin)->get('/login')->assertRedirect(route('admin.dashboard'));
 }

 public function test_khmer_translation_on_front_page_and_backend_blocks():void {
  $this->seed();
  $this->post('/language/km')->assertOk();

  $response = $this->get('/');
  $response->assertOk();
  $response->assertSee('ទំព័រដើម');
  $response->assertSee('ហេតុអ្វី GGS?');
  $response->assertSee('សសរស្តម្ភទាំង៦');
  $response->assertSee('ថ្នាក់ពន្លឿន');
  $response->assertSee('បម្រើសហគមន៍');
  $response->assertSee('សុវត្ថិភាព');
  $response->assertSee('ទំនាក់ទំនង');
  $response->assertSee('បេះដូងស្នេហាជាតិ គំនិតកម្រិតសកល');
  $response->assertSee('ក្លឹបនវានុវត្តន៍ & មន្ទីរពិសោធន៍ STEM');
  $response->assertSee('ក្លឹបសិល្បៈច្នៃប្រឌិត & តន្ត្រីសហគមន៍');
  $response->assertSee('ក្លឹបសុខភាព & កីឡាអត្តពលកម្ម');
  $response->assertSee('ក្លឹបអ្នកការពារធម្មជាតិបៃតង');

  $admin = $this->admin();
  $permission = Permission::firstOrCreate(['slug' => 'manage-content'], ['name' => 'Manage content']);
  if (! $admin->role->permissions->contains($permission->id)) {
      $admin->role->permissions()->attach($permission->id);
  }

  $heroBlock = Block::where('key', 'hero_main')->firstOrFail();
  $this->actingAs($admin)->postJson('/admin/blocks/' . $heroBlock->id, [
      'section' => 'hero',
      'key' => 'hero_main',
      'title' => [
          'en' => 'English Headline Updated',
          'km' => 'ចំណងជើងភាសាខ្មែរថ្មី ២០២៦',
      ],
      'subtitle' => [
          'en' => 'English Subtitle',
          'km' => 'ពាក្យស្លោកខ្មែរថ្មី',
      ],
      'sort_order' => 0,
      'published' => 1,
  ])->assertOk();

  $this->post('/language/km')->assertOk();
  $this->get('/')->assertSee('ចំណងជើងភាសាខ្មែរថ្មី ២០២៦')->assertSee('ពាក្យស្លោកខ្មែរថ្មី');
 }

 public function test_admin_header_hero_form_updates_frontend():void {
  $this->seed();
  $admin = $this->admin();
  $permission = Permission::firstOrCreate(['slug' => 'manage-content'], ['name' => 'Manage content']);
  if (! $admin->role->permissions->contains($permission->id)) {
      $admin->role->permissions()->attach($permission->id);
  }

  // Admin visits the Header / Hero management form
  $this->actingAs($admin)->get('/admin/header')->assertOk()->assertSee('Hero Section');

  // Admin updates Hero section
  $this->actingAs($admin)->postJson('/admin/header', [
      'title' => [
          'en' => 'Leading 21st Century Education in Cambodia',
          'km' => 'ការអប់រំសតវត្សរ៍ទី២១ ឈានមុខគេនៅកម្ពុជា',
      ],
      'subtitle' => [
          'en' => 'Excellence & Global Character',
          'km' => 'ឧត្តមភាព និងចរិយាសម្បត្តិកម្រិតសកល',
      ],
      'body' => [
          'en' => 'Providing transformative STEAM, English fluency, and leadership.',
          'km' => 'ផ្តល់ជូននូវការអប់រំ STEAM ភាសាអង់គ្លេស និងភាពជាអ្នកដឹកនាំដ៏ឆ្នើម។',
      ],
      'cta_text' => [
          'en' => 'Enroll Your Child 2026',
          'km' => 'ចុះឈ្មោះកូនលោកអ្នក ២០២៦',
      ],
      'link_url' => '#pillars',
      'link_text' => [
          'en' => 'Discover Pillars',
          'km' => 'ស្វែងយល់សសរស្តម្ភ',
      ],
      'highlights' => [
          ['en' => 'MoEYS Quality Checked', 'km' => 'ត្រួតពិនិត្យគុណភាពដោយក្រសួង'],
          ['en' => 'Advanced Robotics', 'km' => 'មន្ទីរពិសោធន៍មនុស្សយន្ត'],
          ['en' => 'Safe Campus', 'km' => 'បរិវេណមានសុវត្ថិភាពខ្ពស់'],
      ],
      'published' => 1,
  ])->assertOk();

  // Check English frontend
  $resEn = $this->get('/');
  $resEn->assertOk();
  $resEn->assertSee('Leading 21st Century Education in Cambodia');
  $resEn->assertSee('Excellence & Global Character');
  $resEn->assertSee('Enroll Your Child 2026');
  $resEn->assertSee('Discover Pillars');

  // Check Khmer frontend
  $this->post('/language/km')->assertOk();
  $resKm = $this->get('/');
  $resKm->assertOk();
  $resKm->assertSee('ការអប់រំសតវត្សរ៍ទី២១ ឈានមុខគេនៅកម្ពុជា');
  $resKm->assertSee('ឧត្តមភាព និងចរិយាសម្បត្តិកម្រិតសកល');
  $resKm->assertSee('ចុះឈ្មោះកូនលោកអ្នក ២០២៦');
  $resKm->assertSee('ស្វែងយល់សសរស្តម្ភ');
 }

 public function test_campus_location_map_image_and_link():void {
  $this->seed();
  $res = $this->get('/');
  $res->assertOk();
  $res->assertSee('Explore Campus Locations');
  $res->assertSee('images/campus-map.jpg');
  $res->assertSee('https://maps.app.goo.gl/wR2Gct7vMwnT6T9H8');
 }

 public function test_email_link_opens_gmail_compose():void {
  $this->seed();
  $res = $this->get('/');
  $res->assertOk();
  $res->assertSee('https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox?compose=new', false);
 }
}
