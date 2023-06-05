<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Models\EditorFile;

class EditorFileController extends Controller
{
    public function index()
    {
        $files = EditorFile::all();
        return view('backend.system.editor-files.index', ['files' => $files]);
    }

    public function destroy(EditorFile $editorFile)
    {
        removeFile($editorFile->path);
        $editorFile->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}