<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{
    public function index(Request $request, $search = null)
    {
        $unviewedUsers = User::where('is_viewed', 0)->get();
        if ($unviewedUsers->count() > 0) {
            foreach ($unviewedUsers as $unviewedUser) {
                $unviewedUser->is_viewed = 1;
                $unviewedUser->save();
            }
        }
        $activeUsersCount = User::where('status', 1)->get()->count();
        $bannedUserscount = User::where('status', 0)->get()->count();
        if ($request->input('search')) {
            $q = $request->input('search');
            $users = User::where('firstname', 'like', '%' . $q . '%')
                ->OrWhere('lastname', 'like', '%' . $q . '%')
                ->OrWhere('email', 'like', '%' . $q . '%')
                ->OrWhere('mobile', 'like', '%' . $q . '%')
                ->orderbyDesc('id')
                ->paginate(30);
            $users->appends(['search' => $q]);
        } elseif ($request->input('filter')) {
            $filter = $request->input('filter');
            $arr = ['active', 'banned'];
            abort_if(!in_array($filter, $arr), 404);
            $status = ($filter == 'active') ? 1 : 0;
            $users = User::where('status', $status)->orderbyDesc('id')->paginate(30);
            $users->appends(['filter' => $filter]);
        } else {
            $users = User::orderbyDesc('id')->paginate(30);
        }
        return view('backend.users.index', [
            'users' => $users,
            'activeUsersCount' => $activeUsersCount,
            'bannedUserscount' => $bannedUserscount,
        ]);
    }

    public function create()
    {
        $password = Str::random(16);
        return view('backend.users.create', ['password' => $password]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'email', 'string', 'max:100', 'unique:users'],
            'country' => ['required', 'integer', 'exists:countries,id'],
            'mobile_code' => ['required', 'integer', 'exists:countries,id'],
            'mobile' => ['required', 'numeric', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $country = Country::find($request->country);
        $mobileCountry = Country::find($request->mobile_code);
        if ($request->has('avatar')) {
            $avatar = imageUpload($request->file('avatar'), 'images/avatars/users/', '110x110');
        } else {
            $avatar = "images/avatars/default.png";
        }
        $mobile = $mobileCountry->phone . $request->mobile;
        $existMobile = User::where('mobile', $mobile)->first();
        if ($existMobile) {
            toastr()->error(admin_lang('Phone number already exist'));
            return back()->withInput();
        }
        $address = ['address_1' => '', 'address_2' => '', 'city' => '', 'state' => '', 'zip' => '', 'country' => $country->name];
        $user = User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $mobile,
            'address' => $address,
            'avatar' => $avatar,
            'password' => Hash::make($request->password),
        ]);
        if ($user) {
            if (settings('actions')->email_verification_status) {
                $user->forceFill(['email_verified_at' => Carbon::now()])->save();
            }
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.users.edit', $user->id);
        }
    }

    public function show(User $user)
    {
        return abort(404);
    }

    public function edit(User $user)
    {
        return view('backend.users.edit.index', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'string', 'max:100', 'unique:users,email,' . $user->id],
            'mobile' => ['required', 'string', 'max:50', 'unique:users,mobile,' . $user->id],
            'address_1' => ['nullable', 'max:255'],
            'address_2' => ['nullable', 'max:255'],
            'city' => ['nullable', 'max:150'],
            'state' => ['nullable', 'max:150'],
            'zip' => ['nullable', 'max:100'],
            'country' => ['nullable', 'integer', 'exists:countries,id'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $country = Country::find($request->country);
        $status = ($request->has('status')) ? 1 : 0;
        $google2fa_status = ($request->has('google2fa_status')) ? 1 : 0;
        $address = [
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $country->name,
        ];
        $update = $user->update([
            'name' => $request->firstname . ' ' . $request->lastname,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $address,
            'google2fa_status' => $google2fa_status,
            'status' => $status,
        ]);
        if ($update) {
            $emailValue = ($request->has('email_status')) ? Carbon::now() : null;
            $user->forceFill([
                'email_verified_at' => $emailValue,
            ])->save();
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }

    }

    public function destroy(User $user)
    {
        if ($user->avatar != "images/avatars/default.png") {
            removeFile($user->avatar);
        }
        deleteAdminNotification(route('admin.users.edit', $user->id));
        $user->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }

    public function changeAvatar(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return response()->json(['error' => $error]);
            }
        }
        if ($request->has('avatar')) {
            if ($user->avatar == 'images/avatars/default.png') {
                $avatar = imageUpload($request->file('avatar'), 'images/avatars/users/', '110x110');
            } else {
                $avatar = imageUpload($request->file('avatar'), 'images/avatars/users/', '110x110', null, $user->avatar);
            }
        } else {
            return response()->json(['error' => admin_lang('Upload error')]);
        }
        $update = $user->update([
            'avatar' => $avatar,
        ]);
        if ($update) {
            return response()->json(['success' => admin_lang('Updated Successfully')]);
        }
    }

    public function deleteAvatar(User $user)
    {
        $avatar = "images/avatars/default.png";
        if ($user->avatar != $avatar) {
            removeFile($user->avatar);
        } else {
            toastr()->error(admin_lang('Default avatar cannot be deleted'));
            return back();
        }
        $update = $user->update([
            'avatar' => $avatar,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Removed Successfully'));
            return back();
        }
    }

    public function sendMail(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string'],
            'reply_to' => ['required', 'email'],
            'message' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!settings('smtp')->status) {
            toastr()->error(admin_lang('SMTP is not enabled'));
            return back()->withInput();
        }
        try {
            $email = $user->email;
            $subject = $request->subject;
            $replyTo = $request->reply_to;
            $msg = $request->message;
            \Mail::send([], [], function ($message) use ($msg, $email, $subject, $replyTo) {
                $message->to($email)
                    ->replyTo($replyTo)
                    ->subject($subject)
                    ->html($msg);
            });
            toastr()->success(admin_lang('Sent successfully'));
            return back();
        } catch (\Exception $e) {
            toastr()->error(admin_lang('Sent error'));
            return back();
        }
    }

    public function logs(User $user)
    {
        $logs = UserLog::where('user_id', $user->id)->select('id', 'ip', 'location')->orderbyDesc('id')->paginate(6);
        return view('backend.users.edit.logs', ['user' => $user, 'logs' => $logs]);
    }

    public function getLogs(User $user, UserLog $userLog)
    {
        $userLog['ip_link'] = route('admin.users.logsbyip', $userLog->ip);
        return response()->json($userLog);
    }

    public function logsByIp($ip)
    {
        $logs = UserLog::where('ip', $ip)->with('user')->paginate(12);
        if ($logs->isEmpty()) {
            return abort(404);
        }
        return view('backend.users.logs', ['logs' => $logs]);
    }
}