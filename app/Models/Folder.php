<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory, HasUuids;


    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function parent() {
        return $this->belongsTo(Folder::class, 'parentId');
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->parent ? $this->parent->path . '/' . $this->id : $this->id,
        );
    }
}
