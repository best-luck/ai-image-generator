<?php
namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\StorageProvider;
use Illuminate\Http\Request;
use Storage;

class StorageController extends Controller
{

    public function index()
    {
        $storageProviders = StorageProvider::all();
        return view('backend.settings.storage.index', ['storageProviders' => $storageProviders]);
    }

    public function edit(StorageProvider $storageProvider)
    {
        abort_if($storageProvider->isLocal(), 403);
        return view('backend.settings.storage.edit', ['storageProvider' => $storageProvider]);
    }

    public function update(Request $request, StorageProvider $storageProvider)
    {
        abort_if($storageProvider->isLocal(), 403);
        foreach ($request->credentials as $key => $value) {
            if (!array_key_exists($key, (array) $storageProvider->credentials)) {
                toastr()->error(admin_lang('Mismatch credentials'));
                return back();
            }
        }
        if ($request->has('status')) {
            foreach ($request->credentials as $key => $value) {
                if (empty($value)) {
                    toastr()->error(str_replace('_', ' ', $key) . ' ' . admin_lang('cannot be empty'));
                    return back();
                }
            }
            $request->status = 1;
        } else {
            if ($storageProvider->isDefault()) {
                toastr()->error(admin_lang('Default storage cannot disabled'));
                return back();
            }
            $request->status = 0;
        }
        $update = $storageProvider->update([
            'status' => $request->status,
            'credentials' => $request->credentials,
            'unaccepted_file_types' => $request->unaccepted_file_types,
        ]);
        if ($update) {
            $storageProvider->handler::setCredentials($storageProvider);
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }

    }

    public function setDefault(Request $request, StorageProvider $storageProvider)
    {
        if (!$storageProvider->status) {
            toastr()->error($storageProvider->name . ' ' . admin_lang('is disabled'));
            return back();
        }
        setEnv('FILESYSTEM_DRIVER', $storageProvider->alias);
        toastr()->success($storageProvider->name . ' ' . admin_lang('is now default storage'));
        return back();
    }

    public function storageTest(Request $request, StorageProvider $storageProvider)
    {
        abort_if($storageProvider->isLocal(), 403);
        if (!$storageProvider->status) {
            toastr()->error($storageProvider->name . ' ' . admin_lang('is disabled'));
            return back();
        }
        try {
            $disk = Storage::disk($storageProvider->alias);
            $upload = $disk->put('test.txt', 'public');
            if ($upload) {
                $disk->delete('test.txt');
                toastr()->success($storageProvider->name . ' ' . admin_lang('Connected Successfully'));
                return back();
            } else {
                toastr()->error($storageProvider->name . ' ' . admin_lang('Connection error'));
                return back();
            }
        } catch (\Exception$e) {
            toastr()->error($storageProvider->name . ' ' . admin_lang('Connection error'));
            return back();
        }

    }
}