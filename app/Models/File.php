<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
      'labels' => 'array',
    ];

    protected $attributes = [
      'labels' => '[]',
    ];

    protected $fillable = [
      'name', 'hashed_name', 'owned_by', 'parent_id'
    ];

    protected static function boot() {
      parent::boot();

      static::deleting(function($file) {
        Storage::delete($file->realPath());
      });
    }

    public function owner() {
      return $this->belongsTo(User::class, 'owned_by');
    }

    public function parent() {
      return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function path(): Attribute
    {
      return Attribute::make(
          get: fn () => $this->parent ? $this->parent->path . '/' . $this->name : $this->name,
      );
    }

    public function realPath() {
      return $this->owner->id . '/' . $this->hashed_name;
    }
}
