<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model {protected $fillable=['key','value'];protected function casts():array{return ['value'=>'array'];} public static function valueFor($key,$default=''){ $v=self::where('key',$key)->value('value'); if(is_string($v)) $v=json_decode($v,true);return $v[app()->getLocale()] ?? $v['en'] ?? $default;}}
