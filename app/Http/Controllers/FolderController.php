<?php

namespace App\Http\Controllers;

use App\Http\Requests\Folder\CreateRequest;
use App\Http\Requests\Folder\UpdateRequest;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class FolderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
      $this->authorize('create', Folder::class);

      Folder::create([
        'name' => $request->name,
        'parent_id' => $request->parentId,
        'owned_by' => Auth::user()->id,
      ]);

      return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Folder $folder)
    {
      $this->authorize('update', $folder);

      $folder->update($request->validated());

      return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
      $this->authorize('delete', $folder);

      $previousRouteName = Route::getRoutes()
                        ->match(Request::create(
                          URL::previous()
                        ))
                        ->getName();
      $folder->delete();

      if($previousRouteName == 'app.dashboard')
        return redirect()->route('app.dashboard', ['folder' => $folder->parent]);
      else
        return redirect()->back();
    }
}
