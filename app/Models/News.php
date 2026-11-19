<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'branch_id',
        'slug',
        'category',
        'title',
        'summary',
        'body',
        'image',
        'author',
        'read_time',
        'is_featured',
        'published',
        'published_at',
        'views_count',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'summary' => 'array',
            'body' => 'array',
            'is_featured' => 'boolean',
            'published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (News $news) {
            if (empty($news->published_at)) {
                $news->published_at = now();
            }
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title['en'] ?? 'news-' . uniqid());
            }
            if (empty($news->image)) {
                $news->image = $news->generateBrandedSvg();
            }
        });

        static::updating(function (News $news) {
            if (empty($news->image)) {
                $news->image = $news->generateBrandedSvg();
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)
                     ->where(function ($q) {
                         $q->whereNull('branch_id')
                           ->orWhereHas('branch', fn ($b) => $b->where('published', true));
                     });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function text(string $field, string $default = ''): string
    {
        $val = $this->{$field} ?? [];
        if (! is_array($val)) {
            return (string) $val;
        }
        $locale = app()->getLocale();

        return ! empty($val[$locale]) ? $val[$locale] : (! empty($val['en']) ? $val['en'] : (! empty($val['km']) ? $val['km'] : $default));
    }

    public function textFor(string $field, string $locale, string $default = ''): string
    {
        $val = $this->{$field} ?? [];
        if (! is_array($val)) {
            return (string) $val;
        }

        return ! empty($val[$locale]) ? $val[$locale] : (! empty($val['en']) ? $val['en'] : (! empty($val['km']) ? $val['km'] : $default));
    }

    public function imageUrl(): ?string
    {
        if (! $this->exists && empty($this->image)) {
            return null;
        }

        if (empty($this->image)) {
            $generated = $this->generateBrandedSvg();
            $this->image = $generated;
            if ($this->exists) {
                $this->saveQuietly();
            }
        }

        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'school/') || str_starts_with($this->image, 'news/')) {
            return asset('storage/' . $this->image);
        }

        return asset($this->image);
    }

    /**
     * Auto-generate a beautiful SVG banner image for this news article.
     */
    public function generateBrandedSvg(): string
    {
        $slug = $this->slug ?: 'news-' . uniqid();
        $title = htmlspecialchars($this->title['en'] ?? 'GGS School News Announcement', ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars(strtoupper($this->category ?: 'CAMPUS NEWS'), ENT_QUOTES, 'UTF-8');
        $date = $this->published_at ? $this->published_at->format('d M Y') : date('d M Y');

        // Shorten title for display if needed
        $displayTitle = Str::limit($title, 65, '...');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 675" width="1200" height="675">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0B2240"/>
      <stop offset="50%" stop-color="#14355E"/>
      <stop offset="100%" stop-color="#07162B"/>
    </linearGradient>
    <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#F5A623"/>
      <stop offset="100%" stop-color="#DF9312"/>
    </linearGradient>
    <pattern id="gridPattern" width="40" height="40" patternUnits="userSpaceOnUse">
      <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
    </pattern>
  </defs>

  <!-- Background -->
  <rect width="1200" height="675" fill="url(#bgGrad)"/>
  <rect width="1200" height="675" fill="url(#gridPattern)"/>

  <!-- Decorative Glows & Geometry -->
  <circle cx="1100" cy="100" r="320" fill="rgba(245, 166, 35, 0.08)" filter="blur(60px)"/>
  <circle cx="100" cy="600" r="280" fill="rgba(30, 96, 145, 0.20)" filter="blur(50px)"/>
  
  <g transform="translate(900, 180)" opacity="0.12" stroke="#F5A623" stroke-width="2" fill="none">
    <polygon points="120,20 180,120 60,120"/>
    <circle cx="120" cy="90" r="80"/>
    <rect x="40" y="40" width="160" height="160" rx="20"/>
  </g>

  <!-- Top Header / Brand Crest -->
  <g transform="translate(80, 80)">
    <rect x="0" y="0" width="64" height="64" rx="16" fill="#0B2240" stroke="#F5A623" stroke-width="2.5"/>
    <text x="32" y="42" font-family="Arial, Helvetica, sans-serif" font-size="24" font-weight="900" fill="#F5A623" text-anchor="middle">GGS</text>
    <text x="80" y="28" font-family="Arial, Helvetica, sans-serif" font-size="18" font-weight="bold" fill="#FFFFFF" letter-spacing="1">GLOBAL GENERATION SCHOOL</text>
    <text x="80" y="48" font-family="Arial, Helvetica, sans-serif" font-size="12" font-weight="600" fill="#F5A623" letter-spacing="2">SIEM REAP • OFFICIAL PUBLICATION</text>
  </g>

  <!-- Category Pill Badge -->
  <g transform="translate(80, 220)">
    <rect x="0" y="0" width="220" height="38" rx="19" fill="url(#goldGrad)"/>
    <text x="110" y="24" font-family="Arial, Helvetica, sans-serif" font-size="13" font-weight="bold" fill="#0B2240" text-anchor="middle" letter-spacing="1.5">{$category}</text>
  </g>

  <!-- News Title -->
  <text x="80" y="320" font-family="Arial, Helvetica, sans-serif" font-size="44" font-weight="800" fill="#FFFFFF" width="1040">
    {$displayTitle}
  </text>

  <!-- Decorative Separator Line -->
  <rect x="80" y="380" width="180" height="5" rx="2.5" fill="url(#goldGrad)"/>

  <!-- Footer Metadata -->
  <g transform="translate(80, 560)">
    <text x="0" y="0" font-family="Arial, Helvetica, sans-serif" font-size="16" font-weight="bold" fill="#F5A623">
      ★ Published: {$date}
    </text>
    <text x="300" y="0" font-family="Arial, Helvetica, sans-serif" font-size="16" font-weight="normal" fill="#94A3B8">
      • By GGS Editorial Board
    </text>
    <text x="560" y="0" font-family="Arial, Helvetica, sans-serif" font-size="16" font-weight="normal" fill="#94A3B8">
      • 3 Min Read
    </text>
  </g>
</svg>
SVG;

        $relativePath = 'news/auto-' . $slug . '.svg';
        Storage::disk('public')->put($relativePath, $svg);

        return $relativePath;
    }
}
