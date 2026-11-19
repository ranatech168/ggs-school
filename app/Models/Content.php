<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Content extends Model {
    protected $fillable=['branch_id','type','slug','title','body','image','published','sort_order'];
    protected function casts():array{return ['title'=>'array','body'=>'array','published'=>'boolean'];}
    public function branch(){return $this->belongsTo(Branch::class);}
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
    public function imageUrl(): ?string {
        if (empty($this->image)) { return null; }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) { return $this->image; }
        if (str_starts_with($this->image, 'school/') || str_starts_with($this->image, 'news/')) { return asset('storage/' . $this->image); }
        return asset($this->image);
    }
}

