<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'key',
        'title',
        'subtitle',
        'body',
        'image',
        'icon',
        'link_url',
        'link_text',
        'extra',
        'published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'subtitle' => 'array',
            'body' => 'array',
            'link_text' => 'array',
            'extra' => 'array',
            'published' => 'boolean',
        ];
    }

    public static function sections(): array
    {
        return [
            'hero' => 'Hero Banner',
            'metrics' => 'Summary Metrics (Key Stats)',
            'why_ggs' => 'Why GGS (Vision & Shift)',
            'pillars' => '6 Strategic Pillars',
            'accelerated' => 'Accelerated Learning (ALP)',
            'service_learning' => 'Service Learning & Showcases',
            'action_clubs' => 'GGS Action Clubs',
            'news' => 'News & Updates',
            'child_protection' => 'Child Protection & Safety',
        ];
    }

    public function sectionLabel(): string
    {
        return self::sections()[$this->section] ?? ucfirst(str_replace('_', ' ', $this->section));
    }

    public function text(string $field, string $default = ''): string
    {
        $val = $this->{$field} ?? [];
        if (is_string($val)) {
            return $val;
        }

        $locale = app()->getLocale();

        return !empty($val[$locale]) ? $val[$locale] : (!empty($val['en']) ? $val['en'] : (!empty($val['km']) ? $val['km'] : $default));
    }

    public function textFor(string $field, string $locale, string $default = ''): string
    {
        $val = $this->{$field} ?? [];
        if (is_string($val)) {
            return $val;
        }

        return !empty($val[$locale]) ? $val[$locale] : (!empty($val['en']) ? $val['en'] : (!empty($val['km']) ? $val['km'] : $default));
    }
}

