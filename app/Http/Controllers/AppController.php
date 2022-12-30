<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Inertia\Inertia;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Folder $folder = null)
    {
        $rootFolder = Folder::find('3ced5817-dd29-478c-b91e-54851eaaaa35');
        $folderNodes = Folder::where('parent_id', $rootFolder->id)
                    ->with('nodes')
                    ->get();
        $files = File::where('parent_id', $folder ? $folder->id : $rootFolder->id)
                    ->get();

        return Inertia::render('Dashboard', [
          'folderNodes' => $folderNodes,
          'files' => $files,
          'folderId' => $folder ? $folder->id : $rootFolder-> id,
        ]);
    }
}
