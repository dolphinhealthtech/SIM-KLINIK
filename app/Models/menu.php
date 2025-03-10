<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'icon', 'role', 'parent_id', 'order'];

    public function children() {
        return $this->hasMany(menu::class, 'parent_id', 'id');
    }

}
