<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Folder extends Model
{
  use HasFactory, HasUuids;

  protected $fillable = ['name', 'parent_id', 'owned_by'];

  protected static function boot() {
    parent::boot();

    static::deleting(function($folder) {
        foreach($folder->files as $file){
          $file->delete();
        }

        foreach($folder->children as $subFolder){
          $subFolder->delete();
        }
    });
  }

  public function owner() {
    return $this->belongsTo(User::class);
  }

  public function parent() {
    return $this->belongsTo(Folder::class, 'parent_id');
  }

  public function children() {
    return $this->hasMany(Folder::class, 'parent_id');
  }

  public function files() {
    return $this->hasMany(File::class, 'parent_id');
  }

  public function nodes() {
    return $this->children()->with('nodes');
  }

  public function path(): Attribute
  {
    return new Attribute(
      get: fn () => $this->parent ?
        $this->parent->path . '/' . $this->name :
        $this->name,
    );
  }
}
