<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'slug',
        'phone',
        'email',
        'image',
        'published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'address' => 'array',
            'description' => 'array',
            'published' => 'boolean',
        ];
    }

    public function text(string $key, string $default = ''): string
    {
        $v = $this->$key ?? [];
        if (! is_array($v)) {
            return (string) $v;
        }
        $locale = app()->getLocale();

        return ! empty($v[$locale]) ? $v[$locale] : (! empty($v['en']) ? $v['en'] : (! empty($v['km']) ? $v['km'] : $default));
    }

    public function textFor(string $key, string $locale, string $default = ''): string
    {
        $v = $this->$key ?? [];
        if (! is_array($v)) {
            return (string) $v;
        }

        return ! empty($v[$locale]) ? $v[$locale] : (! empty($v['en']) ? $v['en'] : (! empty($v['km']) ? $v['km'] : $default));
    }
}

