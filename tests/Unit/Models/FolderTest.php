<?php

use App\Models\File;
use App\Models\Folder;
use App\Models\User;

it('belongs to a user', function() {
  $folder = Folder::factory()
              ->for(User::factory()->create(), 'owner')
              ->create();

  expect($folder->owner)->not()->toBeEmpty();
  expect($folder->owner)->toBeInstanceOf(User::class);
});

it('belongs to a parent folder', function () {
  $user = User::factory()->create();
  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->create();
  $folder = Folder::factory()
              ->for($user, 'owner')
              ->for($parentFolder, 'parent')
              ->create();

  expect($folder->parent)->not()->toBeEmpty();
  expect($folder->parent)->toBeInstanceOf(Folder::class);
});

it('has child folders', function () {
  $user = User::factory()->create();
  $folders = Folder::factory()
              ->count(3)
              ->for($user, 'owner');

  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->has($folders, 'children')
                    ->create();

  expect($parentFolder->children)->not()->toBeEmpty();
  expect($parentFolder->children->count())->toEqual(3);
});

it('has many files', function() {
  $user = User::factory()->create();
  $files = File::factory()
            ->count(3)
            ->for($user, 'owner');

  $folder = Folder::factory()
              ->for($user, 'owner')
              ->has($files, 'files')
              ->create();

  expect($folder->files)->not()->toBeEmpty();
  expect($folder->files->count())->toEqual(3);
});


it('can produce tree nodes from top down', function () {
  $user = User::factory()->create();
  $folders = Folder::factory()
              ->count(3)
              ->for($user, 'owner');

  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->has($folders, 'children')
                    ->create();

  $node = $parentFolder->nodes()->first();

  expect($parentFolder->nodes)->not()->toBeEmpty();
  expect($parentFolder->nodes->count())->toEqual(3);
  expect($node->nodes)->toBeEmpty();
});

it('has a generated attribute, "path" and its value matches the expectation', function() {
  $user = User::factory()->create();
  $folders = Folder::factory()
              ->count(3)
              ->for($user, 'owner');

  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->has($folders, 'children')
                    ->create();

  $node = $parentFolder->nodes()->first();

  $expectedParentPath = $parentFolder->name;
  $expectedNodePath = $parentFolder->name . '/' . $node->name;

  expect($parentFolder->path)->toBe($expectedParentPath);
  expect($node->path)->toBe($expectedNodePath);
});

it('deletes subfolders and files inside it on delete', function () {
  $user = User::factory()->create();
  $fileFactory = File::factory()
            ->for($user, 'owner');

  $folderFactory = Folder::factory()
              ->for($user, 'owner')
              ->has($fileFactory, 'files');

  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->has($folderFactory, 'children')
                    ->create();

  $folder = $parentFolder->children->first();
  $file = $folder->files->first();

  $parentFolder->delete();

  expect($file->exists())->toBeFalse();
  expect($folder->exists())->toBeFalse();
});
