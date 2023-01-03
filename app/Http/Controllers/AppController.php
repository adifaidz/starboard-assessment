<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Folder $folder = null)
    {
      $rootFolder = Folder::where('parent_id', null)->where('owned_by', Auth::user()->id)->first();
      $folderNodes = Folder::where('parent_id', $rootFolder->id)
                  ->with('nodes')
                  ->get();
      $files = File::where('parent_id', $folder ? $folder->id : $rootFolder->id)
                  ->get();

      return Inertia::render('Dashboard', [
        'folderId' => $folder ? $folder->id : $rootFolder-> id,
        'files' => $files,
        'folderNodes' => $folderNodes,
      ]);
    }

    public function search(Request $request) {
      $validator = Validator::make($request->all(), [
        'q' => 'required|string',
      ]);

      if ($validator->fails()) {
        return redirect()->route('app.dashboard');
      }

      $validated = $validator->validated();

      $rootFolder = Folder::where('parent_id', null)->where('owned_by', Auth::user()->id)->first();
      $folderNodes = Folder::where('parent_id', $rootFolder->id)
                  ->with('nodes')
                  ->get();
      $files = File::where('name', 'LIKE', '%'. $validated['q'] . '%')->get();
      $folders = Folder::where('name','LIKE', '%'. $validated['q'] . '%')->get();
      $folders->map(function ($folder) {
        return $folder->parent->setAppends(['path']);
      });

      return Inertia::render('Search', [
        'files' => $files,
        'folders' => $folders,
        'folderNodes'=> $folderNodes,
      ]);
    }
}
