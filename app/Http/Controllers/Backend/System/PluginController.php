<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Validator;
use Vironeer\System;
use ZipArchive;

class PluginController extends Controller
{
    public function index()
    {
        $plugins = Plugin::all();
        return view('backend.system.plugins.index', ['plugins' => $plugins]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_code' => ['required', 'string'],
            'plugin_files'  => ['required', 'mimes:zip'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        if (in_array(parse_url(url('/'))['host'], ['localhost', '127.0.0.1'])) {
            toastr()->error(admin_lang('Plugins cannot be installed on local server'));
            return back();
        }

        if (!class_exists('ZipArchive')) {
            toastr()->error(admin_lang('ZipArchive extension is not enabled'));
            return back();
        }

        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $request->purchase_code)) {
            if (!preg_match("/^([d-u0-9]{10})-(([d-u0-9]{5})-){3}([d-u0-9]{10})$/i", $request->purchase_code)) {
                toastr()->error(admin_lang('Invalid purchase code'));
                return back();
            }
        }

        try {

            $uploadPath = fileUpload($request->plugin_files, 'plugins/temp/');

            $zip = new ZipArchive;
            $res = $zip->open($uploadPath);

            if ($res != true) {
                removeFile($uploadPath);
                toastr()->error(admin_lang('Could not open the zip file'));
                return back();
            }

            $dir = trim($zip->getNameIndex(0), '/');

            $pluginsTempPath = base_path('plugins/temp/');

            $thisPluginPath = base_path('plugins/temp/' . $dir);

            if (File::exists($thisPluginPath)) {
                removeDirectory($thisPluginPath);
            }

            $res = $zip->extractTo($pluginsTempPath);

            if ($res == true) {
                removeFile($uploadPath);
            }

            $zip->close();

            if (!File::exists($thisPluginPath . '/config.json')) {
                removeFile($thisPluginPath);
                toastr()->error(admin_lang('Config.json is missing'));
                return back();
            }

            $json = json_decode(file_get_contents($thisPluginPath . '/config.json'), true);

            $api = $this->validatePurchaseCode($request->purchase_code, $json['alias']);

            if ($api->status != "success") {
                removeDirectory($thisPluginPath);
                toastr()->error($api->message);
                return back();
            }

            if (strtolower(System::ALIAS) != $json['script_alias']) {
                removeDirectory($thisPluginPath);
                toastr()->error(admin_lang('Invalid action request'));
                return back();
            }

            if (System::VERSION < $json['minimal_script_version']) {
                removeDirectory($thisPluginPath);
                toastr()->error(admin_lang('Plugin require ' . System::ALIAS . ' version ' . $json['minimal_script_version'] . ' or above'));
                return back();
            }

            $pluginExist = Plugin::where('alias', $json['alias'])->first();

            if ($pluginExist) {
                toastr()->error(admin_lang('Plugin is already exists'));
                return back();
            }

            if (!empty($json['remove_directories'])) {
                foreach ($json['remove_directories'] as $remove_directory) {
                    removeDirectory($remove_directory);
                }
            }

            if (!empty($json['remove_files'])) {
                foreach ($json['remove_files'] as $remove_file) {
                    removeFile($remove_file);
                }
            }

            if (!empty($json['directories'])) {
                foreach ($json['directories'][0]['assets'] as $assets_directory) {
                    makeDirectory($assets_directory);
                }
                foreach ($json['directories'][0]['files'] as $files_directory) {
                    makeDirectory(base_path($files_directory));
                }
            }

            if (!empty($json['assets'])) {
                foreach ($json['assets'] as $asset) {
                    File::copy(base_path($asset['root_directory']), $asset['update_directory']);
                }
            }

            if (!empty($json['files'])) {
                foreach ($json['files'] as $file) {
                    File::copy(base_path($file['root_directory']), base_path($file['update_directory']));
                }
            }

            if (!empty($json['sql_file'])) {
                if (file_exists(base_path($json['sql_file']))) {
                    DB::unprepared(file_get_contents(base_path($json['sql_file'])));
                }
            }

            $plugin = Plugin::create([
                "purchase_code" => $api->data->purchase_code,
                "logo"          => url($json['logo']),
                "name"          => $json['name'],
                "alias"         => strtolower($json['alias']),
                "version"       => $json['version'],
                'action_text'   => $json['action_text'],
                'action_link'   => $json['action_link'],
            ]);

            if ($plugin) {
                removeDirectory($thisPluginPath);
                toastr()->success(admin_lang('Plugin has been installed successfully'));
                return back();
            }

        } catch (\Exception$e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function edit(Plugin $plugin)
    {
        return view('backend.system.plugins.edit', ['plugin' => $plugin]);
    }

    public function update(Request $request, Plugin $plugin)
    {
        $validator = Validator::make($request->all(), [
            'plugin_files' => ['required', 'mimes:zip'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        if (in_array(parse_url(url('/'))['host'], ['localhost', '127.0.0.1'])) {
            toastr()->error(admin_lang('Plugins cannot be installed on local server'));
            return back();
        }

        if (!class_exists('ZipArchive')) {
            toastr()->error(admin_lang('ZipArchive extension is not enabled'));
            return back();
        }

        try {

            $uploadPath = fileUpload($request->plugin_files, 'plugins/temp/');

            $zip = new ZipArchive;
            $res = $zip->open($uploadPath);

            if ($res != true) {
                removeFile($uploadPath);
                toastr()->error(admin_lang('Could not open the zip file'));
                return back();
            }

            $dir = trim($zip->getNameIndex(0), '/');

            $pluginsTempPath = base_path('plugins/temp/');

            $thisPluginPath = base_path('plugins/temp/' . $dir);

            if (File::exists($thisPluginPath)) {
                removeDirectory($thisPluginPath);
            }

            $res = $zip->extractTo($pluginsTempPath);

            if ($res == true) {
                removeFile($uploadPath);
            }

            $zip->close();

            if (!File::exists($thisPluginPath . '/config.json')) {
                removeFile($thisPluginPath);
                toastr()->error(admin_lang('Config.json is missing'));
                return back();
            }

            $json = json_decode(file_get_contents($thisPluginPath . '/config.json'), true);

            $api = $this->validatePurchaseCode($plugin->purchase_code, $plugin->alias);

            if ($api->status != "success") {
                removeDirectory($thisPluginPath);
                toastr()->error($api->message);
                return back();
            }

            if (strtolower(System::ALIAS) != $json['script_alias']) {
                removeDirectory($thisPluginPath);
                toastr()->error(admin_lang('Invalid action request'));
                return back();
            }

            if (System::VERSION < $json['minimal_script_version']) {
                removeDirectory($thisPluginPath);
                toastr()->error(admin_lang('Plugin require ' . System::ALIAS . ' version ' . $json['minimal_script_version'] . ' or above'));
                return back();
            }

            if (!empty($json['remove_directories'])) {
                foreach ($json['remove_directories'] as $remove_directory) {
                    removeDirectory($remove_directory);
                }
            }

            if (!empty($json['remove_files'])) {
                foreach ($json['remove_files'] as $remove_file) {
                    removeFile($remove_file);
                }
            }

            if (!empty($json['directories'])) {
                foreach ($json['directories'][0]['assets'] as $assets_directory) {
                    makeDirectory($assets_directory);
                }
                foreach ($json['directories'][0]['files'] as $files_directory) {
                    makeDirectory(base_path($files_directory));
                }
            }

            if (!empty($json['assets'])) {
                foreach ($json['assets'] as $asset) {
                    File::copy(base_path($asset['root_directory']), $asset['update_directory']);
                }
            }

            if (!empty($json['files'])) {
                foreach ($json['files'] as $file) {
                    File::copy(base_path($file['root_directory']), base_path($file['update_directory']));
                }
            }

            if (!empty($json['sql_file'])) {
                if (file_exists(base_path($json['sql_file']))) {
                    DB::unprepared(file_get_contents(base_path($json['sql_file'])));
                }
            }

            $updatePlugin = $plugin->update([
                "logo"        => url($json['logo']),
                "name"        => $json['name'],
                "alias"       => strtolower($json['alias']),
                "version"     => $json['version'],
                'action_text' => $json['action_text'],
                'action_link' => $json['action_link'],
            ]);

            if ($updatePlugin) {
                removeDirectory($thisPluginPath);
                toastr()->success(admin_lang('Plugin has been updated successfully'));
                return back();
            }

        } catch (\Exception$e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    private function validatePurchaseCode($purchaseCode, $alias)
    {
        $client   = new \GuzzleHttp\Client();
        $res      = $client->get(System::LICENSE_URL . '?purchase_code=' . $purchaseCode . '&alias=' . $alias . '&website=' . url('/'));
        $response = json_decode($res->getBody());
        return $response;
    }
}