<?php

namespace Database\Seeders;

use App\Models\{Block, Content, News, Permission, Role, Setting, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'manage-content' => 'Manage all website content',
            'manage-access' => 'Manage users and roles',
            'view-messages' => 'Read contact messages',
        ] as $slug => $name) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $admin = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super administrator']);
        $admin->permissions()->sync(Permission::pluck('id'));

        $editor = Role::firstOrCreate(['slug' => 'editor'], ['name' => 'Editor']);
        $editor->permissions()->sync(Permission::whereIn('slug', ['manage-content', 'view-messages'])->pluck('id'));

        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            if (strlen(env('ADMIN_PASSWORD')) < 12) {
                throw new \RuntimeException('ADMIN_PASSWORD must have at least 12 characters.');
            }
            User::firstOrCreate(
                ['email' => env('ADMIN_EMAIL')],
                ['name' => env('ADMIN_NAME', 'Administrator'), 'role_id' => $admin->id, 'password' => env('ADMIN_PASSWORD')]
            );
        }

        foreach ([
            'school_name' => ['en' => 'Global Generation School', 'km' => 'សាលាជំនាន់សកល', 'zh' => '环球世代学校'],
            'tagline' => ['en' => 'Patriotic Heart, Global Mind', 'km' => 'បេះដូងស្នេហាជាតិ គំនិតកម្រិតសកល', 'zh' => '爱国之心，全球视野'],
            'contact_phone' => ['en' => '088 6666 288 / 012 452 837'],
            'contact_email' => ['en' => 'ggschool2020@gmail.com'],
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Content::firstOrCreate(
            ['slug' => 'welcome'],
            [
                'type' => 'hero',
                'title' => ['en' => 'A brighter future starts here', 'km' => 'អនាគតភ្លឺស្វាងចាប់ផ្តើមពីទីនេះ', 'zh' => '美好未来从这里开始'],
                'body' => ['en' => 'Discover a welcoming place for every learner to grow.', 'km' => 'ស្វែងយល់ពីកន្លែងសិក្សាដ៏កក់ក្តៅសម្រាប់កុមារគ្រប់រូប។', 'zh' => '探索一个让每位学习者成长的温暖环境。'],
                'image' => 'classroom.webp',
                'published' => true,
            ]
        );

        Content::firstOrCreate(
            ['slug' => 'about-our-school'],
            [
                'type' => 'about',
                'title' => ['en' => 'Learning with purpose', 'km' => 'ការសិក្សាប្រកបដោយគោលបំណង', 'zh' => '有目标的学习'],
                'body' => ['en' => 'Welcome to our school community. Update this introduction in the admin dashboard.', 'km' => 'សូមស្វាគមន៍មកកាន់សហគមន៍សាលារបស់យើង។ សូមកែសម្រួលព័ត៌មាននេះក្នុងផ្ទាំងគ្រប់គ្រង។', 'zh' => '欢迎加入我们的学校社区。请在管理后台更新此介绍。'],
                'published' => true,
            ]
        );

        // Seed Frontend Blocks
        $blocks = [
            // 1. HERO BLOCK
            [
                'section' => 'hero',
                'key' => 'hero_main',
                'title' => [
                    'en' => 'A brighter future starts here: Transformative 21st Century Bilingual & Digital Education',
                    'km' => 'ការអប់រំប្រកបដោយគុណភាព និងការផ្លាស់ប្តូរឌីជីថល (អនាគតភ្លឺស្វាងចាប់ផ្តើមពីទីនេះ)',
                    'zh' => '美好未来从这里开始：21世纪双语与数字化变革教育',
                ],
                'subtitle' => [
                    'en' => 'Patriotic Heart, Global Mind',
                    'km' => 'បេះដូងស្នេហាជាតិ គំនិតកម្រិតសកល',
                    'zh' => '爱国之心，全球视野',
                ],
                'body' => [
                    'en' => 'Global Generation School (GGS) in Siem Reap equips Cambodian youth with rigorous academic excellence, fluent English, STEM innovation, AI literacy, life skills, and environmental sustainability.',
                    'km' => 'សាលាជំនាន់សកល (GGS) ក្រុងសៀមរាប ផ្តល់ជូននូវបទពិសោធន៍អប់រំឈានមុខគេ តាមរយៈការរួមបញ្ចូលគ្នារវាងកម្មវិធីសិក្សាជាតិ ភាសាអង់គ្លេស STEM បច្ចេកវិទ្យា AI និរន្តរភាពបរិស្ថាន និងបំណិនជីវិតពិតប្រាកដ។',
                    'zh' => '暹粒环球世代学校（GGS）通过国家课程、英语、STEM、AI技术、环境可持续性和真实生活技能的结合，为柬埔寨青少年提供卓越的教育体验。',
                ],
                'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2000&auto=format&fit=crop',
                'icon' => 'fa-solid fa-heart-pulse',
                'link_url' => '#pillars',
                'link_text' => ['en' => 'Explore 6 Pillars', 'km' => 'ស្វែងយល់សសរស្តម្ភទាំង៦'],
                'extra' => [
                    'highlights' => [
                        ['en' => 'MoEYS Recognized', 'km' => 'ទទួលស្គាល់ដោយ ក្រសួងអប់រំ'],
                        ['en' => 'Hands-on STEM & AI', 'km' => 'រៀន STEM & AI ជាក់ស្តែង'],
                        ['en' => '100% Child Safety', 'km' => 'សុវត្ថិភាពកុមារ១០០%'],
                    ],
                ],
                'published' => true,
                'sort_order' => 1,
            ],

            // 2. METRIC BLOCKS
            [
                'section' => 'metrics',
                'key' => 'metric_pillars',
                'title' => ['en' => 'Strategic Pillars', 'km' => 'សសរស្តម្ភយុទ្ធសាស្ត្រ'],
                'extra' => ['value' => '6'],
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'section' => 'metrics',
                'key' => 'metric_skills',
                'title' => ['en' => 'Core Life Skills', 'km' => 'បំណិនជីវិតគោល'],
                'extra' => ['value' => '10'],
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'section' => 'metrics',
                'key' => 'metric_child',
                'title' => ['en' => 'Child-Centered Focus', 'km' => 'ការយកចិត្តទុកដាក់លើកុមារ'],
                'extra' => ['value' => '100%'],
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'section' => 'metrics',
                'key' => 'metric_stem',
                'title' => ['en' => 'Innovation & Hydroponics', 'km' => 'នវានុវត្តន៍ & Hydroponics'],
                'extra' => ['value' => 'STEM'],
                'published' => true,
                'sort_order' => 4,
            ],

            // 3. WHY GGS BLOCKS
            [
                'section' => 'why_ggs',
                'key' => 'why_ggs_shift',
                'title' => [
                    'en' => 'Moving from Simple Compliance to Authentic Student Empowerment',
                    'km' => 'ផ្លាស់ប្តូរពី "ការចូលរួមសាមញ្ញ" ទៅជា "ការផ្តល់អំណាចដល់សិស្សពិតប្រាកដ"',
                ],
                'subtitle' => [
                    'en' => 'Pedagogical Paradigm Shift',
                    'km' => 'ការផ្លាស់ប្តូរវិធីសាស្ត្របង្រៀន',
                ],
                'body' => [
                    'en' => 'We move beyond routine compliance (where students follow instructions simply for grades) to true empowerment—where students formulate their own inquiries, choose tools, and reflect on personal growth.',
                    'km' => 'យើងផ្លាស់ប្តូរលើសពីការចូលរួមសាមញ្ញ ទៅជាការផ្តល់អំណាចដល់សិស្សពិតប្រាកដ (សិស្សកំណត់សំណួរដោយខ្លួនឯង ជ្រើសរើសវិធីសាស្ត្រ និងឆ្លុះបញ្ចាំងពីការរីកចម្រើន)។',
                ],
                'icon' => 'fa-solid fa-arrows-spin',
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'section' => 'why_ggs',
                'key' => 'why_ggs_whole_child',
                'title' => ['en' => 'Nurturing the Whole Child', 'km' => 'ការបណ្តុះ "កុមារទាំងអស់"'],
                'body' => [
                    'en' => 'Balancing academic excellence with physical fitness, creative mindsets, patriotic conscience, and humanitarian values.',
                    'km' => 'ធ្វើឱ្យមានតុល្យភាពរវាងឧត្តមភាពសិក្សា សុខភាពរាងកាយ ផ្នត់គំនិតច្នៃប្រឌិត មនសិការស្នេហាជាតិ និងមនសិការមនុស្សធម៌។',
                ],
                'icon' => 'fa-solid fa-heart',
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'section' => 'why_ggs',
                'key' => 'why_ggs_pillars',
                'title' => ['en' => '6 Strategic Pillars', 'km' => 'សសរស្តម្ភយុទ្ធសាស្ត្រទាំង៦'],
                'body' => [
                    'en' => 'Integrating National Standards, English, STEM, AI Literacy, Life Skills, and Hydroponics.',
                    'km' => 'រួមបញ្ចូលកម្មវិធីសិក្សាជាតិ ភាសាអង់គ្លេស STEM AI បំណិនជីវិត និង Hydroponics ក្នុងការរៀនសូត្រ។',
                ],
                'icon' => 'fa-solid fa-cubes',
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'section' => 'why_ggs',
                'key' => 'why_ggs_inquiry',
                'title' => ['en' => 'Inquiry & Discovery', 'km' => 'ការសាកសួរ & រកឃើញ'],
                'body' => [
                    'en' => 'Inquiry-driven modules using technology and hands-on discovery to cultivate capable citizens.',
                    'km' => 'ប្រើប្រាស់ម៉ូឌុលបង្រៀនផ្អែកលើការសាកសួរ ដែលរួមបញ្ចូលបច្ចេកវិទ្យា និងការរកឃើញជាក់ស្តែង។',
                ],
                'icon' => 'fa-solid fa-magnifying-glass',
                'published' => true,
                'sort_order' => 4,
            ],
            [
                'section' => 'why_ggs',
                'key' => 'why_ggs_esd',
                'title' => ['en' => 'Aligned with ESD Goals', 'km' => 'ស្របតាមគោលដៅ ESD'],
                'body' => [
                    'en' => 'Directly mapped to Ministry Education for Sustainable Development (ESD) standards for community impact.',
                    'km' => 'ផ្អែកដោយផ្ទាល់ជាមួយគោលដៅអភិវឌ្ឍន៍ប្រកបដោយចីរភាពរបស់ក្រសួងអប់រំ (ESD) សម្រាប់ការចូលរួមចំណែកក្នុងសង្គម។',
                ],
                'icon' => 'fa-solid fa-seedling',
                'published' => true,
                'sort_order' => 5,
            ],

            // 4. 6 STRATEGIC PILLARS BLOCKS
            [
                'section' => 'pillars',
                'key' => 'pillar_1',
                'title' => ['en' => '1. Strong National Curriculum', 'km' => '១. កម្មវិធីសិក្សាជាតិដ៏រឹងមាំ'],
                'subtitle' => ['en' => 'MoEYS Standard', 'km' => 'ស្តង់ដារក្រសួងអប់រំ'],
                'body' => [
                    'en' => 'Strict adherence to Ministry standards ensuring rigorous foundation in Khmer literature, mathematics, history, and civics.',
                    'km' => 'អនុវត្តតាមស្តង់ដារក្រសួងអប់រំ យុវជន និងកីឡា យ៉ាងខ្ជាប់ខ្ជួន ដើម្បីធានាបាននូវមូលដ្ឋានគ្រឹះអក្សរសាស្ត្រ គណិតវិទ្យា និងប្រវត្តិសាស្ត្ររឹងមាំ។',
                ],
                'icon' => 'fa-solid fa-book-bookmark',
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'section' => 'pillars',
                'key' => 'pillar_2',
                'title' => ['en' => '2. English Communication', 'km' => '២. ការទំនាក់ទំនងភាសាអង់គ្លេស'],
                'subtitle' => ['en' => 'Bilingual Immersion', 'km' => 'ភាសាអង់គ្លេសទ្វេភាសា'],
                'body' => [
                    'en' => 'Intensive bilingual immersion developing fluent speaking, reading, writing, and presentation skills.',
                    'km' => 'កម្មវិធីភាសាអង់គ្លេសពីកម្រិតដំបូងដល់កម្រិតខ្ពស់ បណ្តុះសមត្ថភាពស្តាប់ និយាយ អាន និងសរសេរយ៉ាងស្ទាត់ជំនាញ។',
                ],
                'icon' => 'fa-solid fa-language',
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'section' => 'pillars',
                'key' => 'pillar_3',
                'title' => ['en' => '3. STEM & Innovation', 'km' => '៣. STEM និង នវានុវត្តន៍'],
                'subtitle' => ['en' => 'Globe Innovation Lab', 'km' => 'បន្ទប់ពិសោធន៍ STEM'],
                'body' => [
                    'en' => 'Hands-on science and engineering projects, including microscope analysis, model dispensers, and structural mechanics.',
                    'km' => 'ការសិក្សាពិសោធន៍វិទ្យាសាស្ត្រ បច្ចេកវិទ្យា វិស្វកម្ម និងគណិតវិទ្យា តាមរយៈគម្រោងជាក់ស្តែងដូចជា គំរូកង់វិល (Ferris Wheel) និងកែវពង្រីក។',
                ],
                'icon' => 'fa-solid fa-atom',
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'section' => 'pillars',
                'key' => 'pillar_4',
                'title' => ['en' => '4. Digital Learning & AI', 'km' => '៤. ការរៀនឌីជីថល & AI'],
                'subtitle' => ['en' => 'Future Ready', 'km' => 'ត្រៀមខ្លួនសម្រាប់អនាគត'],
                'body' => [
                    'en' => 'Equipping students with modern computer literacy, coding fundamentals, and ethical AI tools.',
                    'km' => 'បំពាក់បំប៉នបច្ចេកវិទ្យាកុំព្យូទ័រ និងឧបករណ៍ AI ទំនើប ដើម្បីឱ្យសិស្សចេះប្រើប្រាស់ឌីជីថលដោយទទួលខុសត្រូវ និងឆ្លាតវៃ។',
                ],
                'icon' => 'fa-solid fa-laptop-code',
                'published' => true,
                'sort_order' => 4,
            ],
            [
                'section' => 'pillars',
                'key' => 'pillar_5',
                'title' => ['en' => '5. 10 Core Life Skills', 'km' => '៥. បំណិនជីវិត (១០ ប្រកាស)'],
                'subtitle' => ['en' => 'Character Building', 'km' => 'ការកសាងចរិយាសម្បត្តិ'],
                'body' => [
                    'en' => 'Cultivating critical thinking, teamwork, emotional resilience, problem-solving, and positive interaction.',
                    'km' => 'បណ្តុះបំណិនដោះស្រាយបញ្ហា ការធ្វើការងារជាក្រុម ការប្រស្រ័យទាក់ទង ការគិតពិចារណា និងការស្គាល់តម្លៃខ្លួនឯង។',
                ],
                'icon' => 'fa-solid fa-hands-holding-child',
                'published' => true,
                'sort_order' => 5,
            ],
            [
                'section' => 'pillars',
                'key' => 'pillar_6',
                'title' => ['en' => '6. Hydroponics & Sustainability', 'km' => '៦. និរន្តរភាពបរិស្ថាន & Hydroponic'],
                'subtitle' => ['en' => 'Green School Model', 'km' => 'គំរូសាលាបៃតង'],
                'body' => [
                    'en' => 'Soil-free hydroponics farming and eco-art projects instilling deep environmental stewardship.',
                    'km' => 'ការដាំបន្លែលើទឹក (Hydroponic) និងសិល្បៈបៃតង (Eco-Art) បណ្តុះស្មារតីស្រឡាញ់ និងថែរក្សាបរិស្ថានធម្មជាតិ។',
                ],
                'icon' => 'fa-solid fa-leaf',
                'published' => true,
                'sort_order' => 6,
            ],

            // 5. ACCELERATED LEARNING PROGRAM BLOCK
            [
                'section' => 'accelerated',
                'key' => 'alp_main',
                'title' => [
                    'en' => 'Accelerated Learning Program (ALP)',
                    'km' => 'ថ្នាក់រៀនពន្លឿន (Accelerated Learning Program)',
                ],
                'subtitle' => [
                    'en' => 'Special Pathway for Over-Age Students',
                    'km' => 'កម្មវិធីពិសេសសម្រាប់សិស្សលើសអាយុ',
                ],
                'body' => [
                    'en' => 'GGS Accelerated Learning Program allows over-age students or those with interrupted schooling to complete 2 grade levels within a single school year without compromising quality.',
                    'km' => 'កម្មវិធីថ្នាក់ពន្លឿនរបស់ GGS ជួយគាំទ្រដល់សិស្សខកខានការសិក្សា ឬលើសអាយុ ឱ្យអាចសិក្សាបញ្ចប់ ២ កម្រិតថ្នាក់ក្នុងរយៈពេលត្រឹមតែ ១ ឆ្នាំសិក្សា ដោយរក្សាបាននូវគុណភាពអប់រំខ្ពស់បំផុត។',
                ],
                'image' => 'https://6af3e067969c6488eb23b43f.imgix.net/sandbox/Gemini_Generated_Image_9xzddb9xzddb9xzd.jpg',
                'extra' => [
                    'badge_stat' => '១០-១៥%',
                    'badge_text_en' => 'Increase in learning efficiency & comprehension retention',
                    'badge_text_km' => 'ការកើនឡើងនូវប្រសិទ្ធភាពរៀនសូត្រ និងការយល់ដឹងរបស់សិស្ស',
                    'levels' => [
                        [
                            'code' => '1+2',
                            'title_en' => 'Level 1 (Grade 1 + Grade 2)',
                            'title_km' => 'កម្រិតទី១ (ថ្នាក់ទី ១ + ទី ២)',
                            'desc_en' => 'Completes Grades 1 & 2 in 1 academic year focusing on literacy & numeracy.',
                            'desc_km' => 'សិក្សាបញ្ចប់ថ្នាក់ទី១ និងទី២ ក្នុងរយៈពេល ១ ឆ្នាំសិក្សា ផ្តោតលើអក្សរសាស្ត្រ និងលេខគណិតដ្ឋាន។',
                        ],
                        [
                            'code' => '3+4',
                            'title_en' => 'Level 2 (Grade 3 + Grade 4)',
                            'title_km' => 'កម្រិតទី២ (ថ្នាក់ទី ៣ + ទី ៤)',
                            'desc_en' => 'Accelerated comprehension in composition, arithmetic & introductory science.',
                            'desc_km' => 'ពង្រឹងការអាន សរសេរ គណិតវិទ្យា និងវិទ្យាសាស្ត្រសង្គមក្នុងល្បឿនពន្លឿន។',
                        ],
                        [
                            'code' => '5+6',
                            'title_en' => 'Level 3 (Grade 5 + Grade 6)',
                            'title_km' => 'កម្រិតទី៣ (ថ្នាក់ទី ៥ + ទី ៦)',
                            'desc_en' => 'Prepares over-age primary students to transition seamlessly into secondary education.',
                            'desc_km' => 'ត្រៀមខ្លួនសម្រាប់ការឡើងទៅអនុវិទ្យាល័យ ជាមួយនឹងការប្រឡងបញ្ចប់កម្រិតបឋមសិក្សា។',
                        ],
                    ],
                ],
                'published' => true,
                'sort_order' => 1,
            ],

            // 6. SERVICE LEARNING BLOCKS
            [
                'section' => 'service_learning',
                'key' => 'service_main',
                'title' => [
                    'en' => 'Grade 6 Community Service Learning Unit',
                    'km' => 'កម្មវិធីសិក្សាអប់រំបម្រើសហគមន៍ (ថ្នាក់ទី៦)',
                ],
                'subtitle' => [
                    'en' => 'Hands-on Service Project',
                    'km' => 'កម្មវិធីសិក្សាផ្អែកលើការអនុវត្ត',
                ],
                'body' => [
                    'en' => 'A comprehensive 10-lesson curriculum transforming classroom concepts into local community impact.',
                    'km' => 'មេរៀនចំនួន ១០ មេរៀនដែលដឹកនាំសិស្សឱ្យស្គាល់សហគមន៍ ស្វែងយល់បញ្ហា និងរួមគ្នាបង្កើតដំណោះស្រាយពិតប្រាកដ។',
                ],
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'section' => 'service_learning',
                'key' => 'service_showcase_1',
                'title' => ['en' => 'Eco-Art & Recycling Display', 'km' => 'ការច្នៃប្រឌិតស្លឹកឈើ និងផ្លាស្ទិកជាសិល្បៈ'],
                'image' => 'https://6af3e067969c6488eb23b43f.imgix.net/sandbox/Gemini_Generated_Image_gn50sign50sign50.jpg',
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'section' => 'service_learning',
                'key' => 'service_showcase_2',
                'title' => ['en' => 'STEM Ferris Wheel & Mechanics', 'km' => 'ការសិក្សាពីចលនា និងវិស្វកម្មសាមញ្ញ'],
                'image' => 'https://6af3e067969c6488eb23b43f.imgix.net/sandbox/Gemini_Generated_Image_uacagsuacagsuaca.jpg',
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'section' => 'service_learning',
                'key' => 'service_showcase_3',
                'title' => ['en' => 'Microscope Biological Study', 'km' => 'ការពិនិត្យកោសិកា និងប្រព័ន្ធជីវសាស្ត្រ'],
                'image' => 'https://6af3e067969c6488eb23b43f.imgix.net/sandbox/Gemini_Generated_Image_yj3i4lyj3i4lyj3i.jpg',
                'published' => true,
                'sort_order' => 4,
            ],

            // 7. ACTION CLUBS BLOCKS
            [
                'section' => 'action_clubs',
                'key' => 'club_stem',
                'title' => ['en' => 'Innovation & STEM Lab', 'km' => 'ក្លឹបនវានុវត្តន៍ & មន្ទីរពិសោធន៍ STEM', 'zh' => '创新与STEM实验室'],
                'body' => [
                    'en' => 'Model building, science experiments, and creative use of AI tools.',
                    'km' => 'ការបង្កើតគំរូ ពិសោធន៍វិទ្យាសាស្ត្រ និងការប្រើប្រាស់ឧបករណ៍ AI។',
                ],
                'icon' => 'fa-solid fa-microchip',
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'section' => 'action_clubs',
                'key' => 'club_arts',
                'title' => ['en' => 'Creative Arts & Harmony', 'km' => 'ក្លឹបសិល្បៈច្នៃប្រឌិត & តន្ត្រីសហគមន៍', 'zh' => '创意艺术与和谐俱乐部'],
                'body' => [
                    'en' => 'Painting, traditional Khmer music, and cultural craft projects.',
                    'km' => 'សិល្បៈគំនូរ សិល្បៈតន្ត្រី និងការច្នៃប្រឌិតវប្បធម៌ខ្មែរ។',
                ],
                'icon' => 'fa-solid fa-palette',
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'section' => 'action_clubs',
                'key' => 'club_sports',
                'title' => ['en' => 'Health & Athletics League', 'km' => 'ក្លឹបសុខភាព & កីឡាអត្តពលកម្ម', 'zh' => '健康与体育联赛'],
                'body' => [
                    'en' => 'Football, basketball, and athletic endurance development.',
                    'km' => 'កីឡាបាល់ទាត់ បាល់បោះ និងសកម្មភាពពង្រឹងសុខភាពរាងកាយ។',
                ],
                'icon' => 'fa-solid fa-person-running',
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'section' => 'action_clubs',
                'key' => 'club_green',
                'title' => ['en' => 'Green Guardians Club', 'km' => 'ក្លឹបអ្នកការពារធម្មជាតិបៃតង', 'zh' => '绿色守护者俱乐部'],
                'body' => [
                    'en' => 'Caring for hydroponics systems and campus tree planting initiatives.',
                    'km' => 'ការថែទាំសួន Hydroponics និងការដាំដើមឈើក្នុងសាលា។',
                ],
                'icon' => 'fa-solid fa-seedling',
                'published' => true,
                'sort_order' => 4,
            ],

            // 8. NEWS & UPDATES (HOME & NEWS PAGE SECTIONS)
            [
                'section' => 'news',
                'key' => 'news_header',
                'title' => [
                    'en' => 'School News & Stories',
                    'km' => 'ព្រឹត្តិការណ៍ថ្មីៗរបស់សាលា',
                ],
                'subtitle' => [
                    'en' => 'Latest News & Updates',
                    'km' => 'ព័ត៌មាន & សកម្មភាព',
                ],
                'body' => [
                    'en' => 'Stay informed with recent achievements, academic activities, student showcases, and announcements from Global Generation School.',
                    'km' => 'តាមដានព័ត៌មាន សមិទ្ធផល សកម្មភាពសិក្សា និងសេចក្តីប្រកាសសំខាន់ៗរបស់សាលាជំនាន់សកល។',
                ],
                'icon' => 'fa-solid fa-newspaper',
                'link_url' => '/news',
                'link_text' => [
                    'en' => 'View All News',
                    'km' => 'មើលព័ត៌មានទាំងអស់',
                ],
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'section' => 'news',
                'key' => 'news_spotlight',
                'title' => [
                    'en' => 'Annual STEM Innovation & Hydroponics Exhibition 2026',
                    'km' => 'ពិព័រណ៍នវានុវត្តន៍ STEM និង Hydroponics ប្រចាំឆ្នាំ ២០២៦',
                ],
                'subtitle' => [
                    'en' => 'Featured Publishing Spotlight',
                    'km' => 'ការចុះផ្សាយព័ត៌មានពិសេស',
                ],
                'body' => [
                    'en' => 'GGS students showcased over 40 working robotic prototypes, AI-driven irrigation systems, and eco-art installations in collaboration with international educational partners.',
                    'km' => 'សិស្សានុសិស្ស GGS បានបង្ហាញនូវគំរូរ៉ូបូតជាង ៤០ ប្រព័ន្ធស្រោចស្រពឆ្លាតវៃដំណើរការដោយ AI និងស្នាដៃសិល្បៈបៃតង សហការជាមួយដៃគូអន្តរជាតិ។',
                ],
                'image' => 'https://6af3e067969c6488eb23b43f.imgix.net/sandbox/Gemini_Generated_Image_uacagsuacagsuaca.jpg',
                'link_url' => '/news',
                'link_text' => [
                    'en' => 'Read Full Spotlight',
                    'km' => 'អានការចុះផ្សាយលម្អិត',
                ],
                'extra' => [
                    'category' => 'STEM & Innovation',
                    'read_time' => '4 min read',
                    'author' => 'GGS Editorial Desk',
                ],
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'section' => 'news',
                'key' => 'news_announcement',
                'title' => [
                    'en' => 'Admissions Open for Academic Year 2026-2027',
                    'km' => 'ការចុះឈ្មោះចូលរៀនសម្រាប់ឆ្នាំសិក្សា ២០២៦-២០២៧ បានចាប់ផ្តើមហើយ',
                ],
                'subtitle' => [
                    'en' => 'Campus Bulletin',
                    'km' => 'សេចក្តីជូនដំណឹងសាលា',
                ],
                'body' => [
                    'en' => 'Early enrollment benefits and sibling discounts available. Visit our admissions office or book a campus tour today.',
                    'km' => 'ទទួលបានអត្ថប្រយោជន៍ពីការចុះឈ្មោះមុនកាលកំណត់។ សូមទាក់ទងមកកាន់ការិយាល័យរដ្ឋបាល ឬកក់ការទស្សនាសាលារៀនឥឡូវនេះ។',
                ],
                'icon' => 'fa-solid fa-bullhorn',
                'link_url' => '#contact-section',
                'link_text' => [
                    'en' => 'Contact Admissions',
                    'km' => 'ទាក់ទងចុះឈ្មោះ',
                ],
                'published' => true,
                'sort_order' => 3,
            ],

            // 9. CHILD PROTECTION & SAFETY BLOCK
            [
                'section' => 'child_protection',
                'key' => 'cp_main',
                'title' => [
                    'en' => 'Child Protection & Safety Policy Framework (UNICEF / MoEYS Aligned)',
                    'km' => 'នយោបាយការពារកុមារ និងសុវត្ថិភាព (UNICEF / MoEYS Aligned)',
                ],
                'subtitle' => [
                    'en' => 'Child Protection First',
                    'km' => 'សុវត្ថិភាពកុមារជាអាទិភាពចម្បង',
                ],
                'body' => [
                    'en' => 'GGS maintains strict adherence to UNICEF and Ministry child protection standards with a zero-tolerance policy against any harm.',
                    'km' => 'សាលាជំនាន់សកល (GGS) យកចិត្តទុកដាក់បំផុតលើសុវត្ថិភាព ផ្លូវកាយ និងផ្លូវចិត្តរបស់សិស្សគ្រប់រូប តាមរយៈគោលការណ៍មិនអត់ឱនដាច់ខាត (Zero Tolerance)។',
                ],
                'icon' => 'fa-solid fa-shield-halved',
                'extra' => [
                    'points' => [
                        [
                            'title_en' => '2-Adult Rule',
                            'title_km' => 'គោលការណ៍មនុស្សចាស់២នាក់',
                            'desc_en' => 'Staff members are never left alone with a student in closed spaces.',
                            'desc_km' => 'មិនអនុញ្ញាតឱ្យបុគ្គលិកនៅម្នាក់ឯងជាមួយសិស្សក្នុងបន្ទប់បិទជិតឡើយ។',
                        ],
                        [
                            'title_en' => '24/7 Campus Security',
                            'title_km' => 'ប្រព័ន្ធសុវត្ថិភាព ២៤/៧',
                            'desc_en' => 'Comprehensive CCTV surveillance and security presence across campus.',
                            'desc_km' => 'បំពាក់កាមេរ៉ាសុវត្ថិភាព CCTV និងសន្តិសុខការពារទូទាំងបរិវេណសាលា។',
                        ],
                        [
                            'title_en' => 'Safety Focal Officers',
                            'title_km' => 'មន្ត្រីទទួលបន្ទុកសុវត្ថិភាព',
                            'desc_en' => 'Dedicated safeguarding officers handling confidential reporting.',
                            'desc_km' => 'មានក្រុមការងារឆ្លើយតប និងទទួលបណ្តឹងតវ៉ាដោយស្ងាត់កំបាំង។',
                        ],
                    ],
                ],
                'published' => true,
                'sort_order' => 1,
            ],
        ];

        foreach ($blocks as $block) {
            Block::updateOrCreate(['key' => $block['key']], $block);
        }

        // 10. SEED INITIAL NEWS PUBLICATIONS
        $newsList = [
            [
                'slug' => 'stem-robotics-national-champions',
                'category' => 'STEM & Innovation',
                'title' => [
                    'en' => 'GGS Students Win National MoEYS Robotics & AI Showcase',
                    'km' => 'សិស្សានុសិស្ស GGS ឈ្នះពានរង្វាន់ជាតិផ្នែករ៉ូបូត និងបញ្ញាសិប្បនិម្មិត AI របស់ក្រសួងអប់រំ',
                ],
                'summary' => [
                    'en' => 'Our young inventors designed an automated hydroponics monitoring robot and secured first place among 60 participating schools.',
                    'km' => 'អ្នកច្នៃប្រឌិតវ័យក្មេងរបស់យើងបានរៀបចំរ៉ូបូតត្រួតពិនិត្យប្រព័ន្ធ Hydroponics ស្វ័យប្រវត្ត និងទទួលបានចំណាត់ថ្នាក់លេខ១ ក្នុងចំណោមសាលាចំនួន៦០។',
                ],
                'body' => [
                    'en' => "We are thrilled to announce that the Global Generation School Robotics Team has won the Gold Trophy at the National STEM & AI Competition hosted in Phnom Penh.\n\nOver the course of five intensive rounds, our Grade 5 and 6 students demonstrated a fully functional IoT hydroponics monitoring device capable of sensing pH levels, water temperature, and ambient humidity using solar power.\n\n\"This victory reflects our school's commitment to giving children hands-on engineering challenges early on,\" stated School Principal Dr. Katherine Wood.",
                    'km' => "យើងខ្ញុំមានសេចក្តីសោមនស្សរីករាយយ៉ាងក្រៃលែងក្នុងការប្រកាសថា ក្រុមរ៉ូបូតរបស់សាលាជំនាន់សកល (GGS) ទទួលបានពានរង្វាន់មាស ក្នុងការប្រកួតប្រជែង STEM & AI ថ្នាក់ជាតិ។\n\nសិស្សានុសិស្សថ្នាក់ទី ៥ និងទី ៦ របស់យើងបានបង្ហាញឧបករណ៍ត្រួតពិនិត្យ Hydroponics ដោយស្វ័យប្រវត្ត ដែលដំណើរការដោយថាមពលពន្លឺព្រះអាទិត្យ និងអាចវាស់កម្រិត pH និងសីតុណ្ហភាពទឹកបានយ៉ាងជាក់លាក់។",
                ],
                'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=1200&auto=format&fit=crop',
                'author' => 'Dr. Katherine Wood',
                'read_time' => '4 min read',
                'is_featured' => true,
                'published' => true,
                'published_at' => now()->subDays(2),
                'views_count' => 384,
                'sort_order' => 1,
            ],
            [
                'slug' => 'kindergarten-green-explorers',
                'category' => 'Early Childhood',
                'title' => [
                    'en' => 'Early Years Green Explorers: Learning Nature Firsthand',
                    'km' => 'កុមារដ្ឋានបៃតង៖ ការរៀនសូត្រពីធម្មជាតិដោយផ្ទាល់ដៃ',
                ],
                'summary' => [
                    'en' => 'Kindergarten students harvested their first organic mint and cherry tomatoes in the school eco-garden.',
                    'km' => 'សិស្សថ្នាក់មត្តេយ្យបានប្រមូលផលជីអង្កាម និងប៉េងប៉ោះធម្មជាតិជាលើកដំបូងនៅក្នុងសួនអេកូឡូស៊ីសាលា។',
                ],
                'body' => [
                    'en' => "Early childhood education at GGS is rooted in sensory, active inquiry. This week, our kindergarten learners spent the morning in our organic garden bed observing seed germination, feeling soil textures, and harvesting fresh herbs.\n\nBy engaging with living plants, our young learners develop deep environmental appreciation, observational curiosity, and healthy nutrition habits.",
                    'km' => "ការអប់រំកុមារតូចនៅ GGS គឺផ្អែកលើការរៀនសូត្រតាមរយៈការអនុវត្តផ្ទាល់។ នៅសប្តាហ៍នេះ កុមារតូចៗរបស់យើងបានចុះសិក្សាដោយផ្ទាល់នៅក្នុងសួនបៃតងធម្មជាតិ ដោយសង្កេតការលូតលាស់នៃគ្រាប់ពូជ និងប្រមូលផលបន្លែស្រស់ៗ។",
                ],
                'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1200&auto=format&fit=crop',
                'author' => 'Teacher Sreymom',
                'read_time' => '3 min read',
                'is_featured' => false,
                'published' => true,
                'published_at' => now()->subDays(5),
                'views_count' => 245,
                'sort_order' => 2,
            ],
            [
                'slug' => 'service-learning-tree-planting-initiative',
                'category' => 'Community Service',
                'title' => [
                    'en' => 'Grade 6 Students Plant 300 Trees in Sala Kamreauk Village',
                    'km' => 'សិស្សថ្នាក់ទី៦ ដាំដើមឈើចំនួន ៣០០ ដើមនៅភូមិសាលាកំរើក ក្រុងសៀមរាប',
                ],
                'summary' => [
                    'en' => 'As part of our 10-lesson community service unit, Grade 6 students partnered with local elders to plant native shade trees.',
                    'km' => 'ជាផ្នែកមួយនៃកម្មវិធីអប់រំបម្រើសហគមន៍ សិស្សថ្នាក់ទី៦ បានសហការជាមួយចាស់ទុំក្នុងភូមិដើម្បីដាំដើមឈើម្លប់ឡើងវិញ។',
                ],
                'body' => [
                    'en' => "Under the mentorship of community leaders, sixty GGS Grade 6 students participated in the Siem Reap Green Corridor initiative. Together with local residents, they planted over 300 native saplings along neighborhood pathways.\n\n\"Service learning taught me that true leadership means taking care of the place you call home,\" shared student representative Chanthy.",
                    'km' => "ក្រោមការណែនាំរបស់អាជ្ញាធរ និងចាស់ទុំក្នុងភូមិ សិស្សថ្នាក់ទី ៦ នៃសាលា GGS បានចូលរួមក្នុងយុទ្ធនាការដាំដើមឈើចំនួន ៣០០ ដើមតាមដងផ្លូវក្នុងសហគមន៍ ដើម្បីបង្កើតម្លប់បៃតង និងកាត់បន្ថយកម្តៅ។",
                ],
                'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=1200&auto=format&fit=crop',
                'author' => 'Sokha Meng',
                'read_time' => '3 min read',
                'is_featured' => false,
                'published' => true,
                'published_at' => now()->subDays(8),
                'views_count' => 195,
                'sort_order' => 3,
            ],
            [
                'slug' => 'bilingual-spelling-bee-champions-2026',
                'category' => 'Academic Excellence',
                'title' => [
                    'en' => 'Celebrating Excellence at the 2026 Bilingual Spelling Bee',
                    'km' => 'អបអរសាទរជោគជ័យនៃការប្រកួតអក្ខរាវិរុទ្ធទ្វេភាសា ២០២៦',
                ],
                'summary' => [
                    'en' => 'Over 120 primary students participated in the annual English and Khmer academic vocabulary competition.',
                    'km' => 'សិស្សបឋមសិក្សាជាង ១២០ នាក់បានចូលរួមក្នុងការប្រកួតវាក្យសព្ទសិក្សាភាសាអង់គ្លេស និងភាសាខ្មែរប្រចាំឆ្នាំ។',
                ],
                'body' => [
                    'en' => "Language mastery is a pillar of 21st-century capability. The 2026 GGS Spelling Bee tested spelling precision, etymology, and pronunciation in both Khmer and English.\n\nCongratulations to our top finalists from Grades 3 through 6 who demonstrated exceptional composure and linguistic fluency.",
                    'km' => "ភាពស្ទាត់ជំនាញផ្នែកភាសា គឺជាសសរស្តម្ភដ៏សំខាន់សម្រាប់សតវត្សរ៍ទី២១។ ការប្រកួតអក្ខរាវិរុទ្ធទ្វេភាសា ២០២៦ បានសាកល្បងភាពច្បាស់លាស់នៃការបញ្ចេញសំឡេង និងការប្រកបពាក្យទាំងជាភាសាខ្មែរ និងអង់គ្លេស។",
                ],
                'image' => null, // Auto-generates branded SVG!
                'author' => 'GGS Academic Board',
                'read_time' => '2 min read',
                'is_featured' => false,
                'published' => true,
                'published_at' => now()->subDays(12),
                'views_count' => 170,
                'sort_order' => 4,
            ],
            [
                'slug' => 'unicef-child-protection-annual-refresher',
                'category' => 'Safeguarding & Policy',
                'title' => [
                    'en' => 'Annual Child Safeguarding Refresher for Faculty & Staff',
                    'km' => 'វគ្គបណ្តុះបណ្តាលពង្រឹងការការពារកុមារប្រចាំឆ្នាំសម្រាប់លោកគ្រូអ្នកគ្រូ',
                ],
                'summary' => [
                    'en' => 'Ensuring our 2-Adult rule, anti-bullying protocols, and digital safety standards remain top priority.',
                    'km' => 'ធានាថាគោលការណ៍មនុស្សចាស់២នាក់ ការទប់ស្កាត់ការគំរាមកំហែង និងសុវត្ថិភាពឌីជីថលនៅតែជាអាទិភាពខ្ពស់បំផុត។',
                ],
                'body' => [
                    'en' => "Child safety is non-negotiable at Global Generation School. All academic and administrative staff completed our annual safeguarding workshop aligned with UNICEF and Ministry guidelines.\n\nThe workshop reinforced our confidential reporting mechanisms, the mandatory 2-adult presence in learning spaces, and proactive student emotional support.",
                    'km' => "សុវត្ថិភាពកុមារគឺជាគោលការណ៍មិនអាចខ្វះបាននៅសាលាជំនាន់សកល។ បុគ្គលិកអប់រំ និងរដ្ឋបាលទាំងអស់បានបញ្ចប់វគ្គបណ្តុះបណ្តាលស្តីពីការការពារកុមារ ស្របតាមគោលការណ៍ណែនាំរបស់ UNICEF និងក្រសួងអប់រំ។",
                ],
                'image' => null, // Auto-generates branded SVG!
                'author' => 'Safeguarding Committee',
                'read_time' => '3 min read',
                'is_featured' => false,
                'published' => true,
                'published_at' => now()->subDays(15),
                'views_count' => 140,
                'sort_order' => 5,
            ],
        ];

        foreach ($newsList as $item) {
            News::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
