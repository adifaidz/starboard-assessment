<?php

use App\Models\File;
use App\Models\Folder;
use App\Models\User;

it('has many folders', function() {
  $user = User::factory()
            ->has(Folder::factory()->count(3), 'folders')
            ->create();

  expect($user->folders)->not()->toBeEmpty();
  expect($user->folders->count())->toEqual(3);
});

it('has many files', function() {
  $user = User::factory()
            ->has(File::factory()->count(2), 'files')
            ->create();

  expect($user->files)->not()->toBeEmpty();
  expect($user->files->count())->toEqual(2);
});
