<?php

namespace App\Http\Controllers\Backend\Navigation;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use App\Models\Language;
use Illuminate\Http\Request;
use Validator;

class FooterMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('lang')) {
            $language = Language::where('code', $request->lang)->firstOrFail();
            $footerMenuLinks = FooterMenu::where('lang', $language->code)->whereNull('parent_id')->with(['children' => function ($query) {
                $query->byOrder();
            }])->byOrder()->get();
            return view('backend.navigation.footerMenu.index', [
                'footerMenuLinks' => $footerMenuLinks,
                'active' => $language->name,
            ]);
        } else {
            return redirect(url()->current() . '?lang=' . env('DEFAULT_LANGUAGE'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.navigation.footerMenu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3', 'exists:languages,code'],
            'name' => ['required', 'string', 'max:100', 'unique:footer_menu'],
            'link' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $countLinks = FooterMenu::all()->count();
        $footerMenu = FooterMenu::create([
            'lang' => $request->lang,
            'name' => $request->name,
            'link' => $request->link,
            'order' => $countLinks + 1,
        ]);
        if ($footerMenu) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect(route('admin.footerMenu.index') . '?lang=' . $footerMenu->lang);
        }
    }

    /**
     *  nestable menu
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nestable(Request $request)
    {
        if ($request->has('ids') && !is_null($request->ids)) {
            $data = json_decode($request->ids, true);
            $i = 1;
            foreach ($data as $arr) {
                $menu = FooterMenu::find($arr['id']);
                $menu->update([
                    'order' => $i,
                    'parent_id' => null,
                ]);
                if (isset($arr['children'])) {
                    $sub_i = 1;
                    foreach ($arr['children'] as $children) {
                        $menu = FooterMenu::find($children['id']);
                        $menu->update([
                            'order' => $sub_i,
                            'parent_id' => $arr['id'],
                        ]);
                        $sub_i++;
                    }
                }
                $i++;
            }
        }
        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FooterMenu  $footerMenu
     * @return \Illuminate\Http\Response
     */
    public function show(FooterMenu $footerMenu)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FooterMenu  $footerMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(FooterMenu $footerMenu)
    {
        return view('backend.navigation.footerMenu.edit', ['footerMenu' => $footerMenu]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FooterMenu  $footerMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FooterMenu $footerMenu)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'string', 'max:3', 'exists:languages,code'],
            'name' => ['required', 'string', 'max:100', 'unique:footer_menu,name,' . $footerMenu->id],
            'link' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $updateMenu = $footerMenu->update([
            'lang' => $request->lang,
            'name' => $request->name,
            'link' => $request->link,
        ]);
        if ($updateMenu) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FooterMenu  $footerMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(FooterMenu $footerMenu)
    {
        $footerMenu->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
