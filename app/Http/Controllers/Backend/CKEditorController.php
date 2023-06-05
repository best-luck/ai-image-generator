<?php

namespace App\Http\Controllers\Backend;

use Validator;
use App\Models\EditorFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return response()->json(['error' => $error], 400);
            }
        }
        $image = imageUpload($request->file('image'), 'media/images/');
        if ($image) {
            $editorFile = new EditorFile();
            $editorFile->path = $image;
            $editorFile->save();
        }
        return response()->json(['uploaded' => true, 'default' => asset($image)]);
    }
}