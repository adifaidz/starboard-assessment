<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\CreateRequest;
use App\Http\Requests\File\UpdateRequest;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CreateRequest $request)
  {
    $this->authorize('create', File::class);

    $uploadedFiles = $request->file('files');
    $parent = Folder::find($request->parentId);

    foreach ($uploadedFiles as $uploadedFile) {
      $file = File::create([
        'name' => $uploadedFile->getClientOriginalName(),
        'owned_by' => Auth::user()->id,
        'hashed_name' => $uploadedFile->hashName(),
        'parent_id' => $parent->id,
      ]);

      $uploadedFile->storeAs($file->owned_by, $file->hashed_name);
    }

    return redirect()->back();
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\File  $file
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request, File $file)
  {
    $this->authorize('update', $file);

    $file->update($request->validated());

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\File  $file
   * @return \Illuminate\Http\Response
   */
  public function destroy(File $file)
  {
    $this->authorize('delete', $file);

    $file->delete();

    return redirect()->back();
  }

  public function download(File $file)
  {
    $this->authorize('view', $file);

    return Storage::download($file->realPath(), $file->name);
  }
}
