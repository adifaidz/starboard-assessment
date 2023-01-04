<?php

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('belongs to a user', function() {
  $file = File::factory()
            ->for(User::factory()->create(), 'owner')
            ->create();

  expect($file->owner)->not()->toBeEmpty();
  expect($file->owner)->toBeInstanceOf(User::class);
});

it('belongs to a parent folder', function() {
  $user = User::factory()->create();
  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->create();
  $file = File::factory()
              ->for($user, 'owner')
              ->for($parentFolder, 'parent')
              ->create();

  expect($file->parent)->not()->toBeEmpty();
  expect($file->parent)->toBeInstanceOf(Folder::class);
});

it('has a generated attribute, "path" and its value matches the expectation', function() {
  $user = User::factory()->create();
  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->create();
  $file = File::factory()
              ->for($user, 'owner')
              ->for($parentFolder, 'parent')
              ->create();

  $expectedFilePath = $parentFolder->path . '/' . $file->name;

  expect($file->path)->toBe($expectedFilePath);
});

it('returns the real path of the file', function () {
  $user = User::factory()->create();
  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->create();
  $file = File::factory()
              ->for($user, 'owner')
              ->for($parentFolder, 'parent')
              ->create();

  $expectedFilePath = $file->owner->id . '/' . $file->hashed_name;

  expect($file->realPath())->toBe($expectedFilePath);
});

it('deletes the file in storage on delete', function () {
  Storage::fake('local');

  $uploadedFile = UploadedFile::fake()->image('photo1.jpg');

  $user = User::factory()->create();
  $parentFolder = Folder::factory()
                    ->for($user, 'owner')
                    ->create();
  $file = File::factory()
          ->create([
            'name' => $uploadedFile->getClientOriginalName(),
            'hashed_name' => $uploadedFile->hashName(),
            'size' => ReadableSize($uploadedFile->getSize(), false),
            'owned_by' => $user->id,
            'parent_id' => $parentFolder->id,
          ]);
  $uploadedFile->storeAs($file->owned_by, $file->hashed_name);

  $file->delete();

  expect($file->exists())->toBeFalse();
  expect(Storage::disk('local')->exists($file->realPath()))->toBeFalse();
});
