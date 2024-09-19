<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Livewire\Feeds;

class Emoji extends Model
{
    use HasFactory;
 // Replace 'your_table' with the actual table name
 protected $table = 'emojis';

 protected $fillable = ['emp_id', 'first_name', 'last_name', 'emoji'];
 public function emojis()
 {
     return $this->hasMany(Emoji::class, 'emp_id', 'emp_id');
 }
 public function getImageUrlAttribute()
 {
     return 'data:image/jpeg;base64,' . base64_encode($this->attributes['image']);
 }
}
