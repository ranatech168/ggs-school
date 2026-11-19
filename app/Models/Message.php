<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Message extends Model {protected $fillable=['name','email','phone','branch_id','message'];public function branch(){return $this->belongsTo(Branch::class);}}
