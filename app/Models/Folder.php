<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
  use HasFactory, HasUuids;

  protected $fillable = ['name', 'parent_id', 'owned_by'];

  public function owner() {
    return $this->belongsTo(User::class);
  }

  public function parent() {
    return $this->belongsTo(Folder::class, 'parent_id');
  }

  public function children() {
    return $this->hasMany(Folder::class, 'parent_id');
  }

  public function nodes() {
    return $this->children()->with('nodes');
  }

  public function path(): Attribute
  {
    return new Attribute(
      get: fn () => $this->parent ? $this->parent->path . '/' . $this->name : $this->name,
    );
  }
}
