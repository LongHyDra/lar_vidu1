<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteNotification extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'url', 'read_at'];
    protected function casts(): array { return ['read_at' => 'datetime']; }
}
