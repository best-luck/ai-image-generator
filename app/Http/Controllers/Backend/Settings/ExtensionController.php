<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Http\Methods\ExtentionCredentials;
use App\Models\Extension;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extensions = Extension::all();
        return view('backend.settings.extensions.index', ['extensions' => $extensions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function edit(Extension $extension)
    {
        return view('backend.settings.extensions.edit', ['extension' => $extension]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Extension $extension)
    {
        foreach ($request->credentials as $key => $value) {
            if (!array_key_exists($key, (array) $extension->credentials)) {
                toastr()->error(admin_lang('Credentials parameter error'));
                return back();
            }
        }

        if ($request->has('status')) {
            foreach ($request->credentials as $key => $value) {
                if (empty($value)) {
                    toastr()->error(str_replace('_', ' ', $key) . admin_lang(' cannot be empty'));
                    return back();
                }
            }
            $request->status = 1;
        } else {
            $request->status = 0;
        }
        $update = $extension->update([
            'status' => $request->status,
            'credentials' => $request->credentials,
        ]);
        if ($update) {
            ExtentionCredentials::credentials($extension);
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }
}
