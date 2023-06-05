<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotification::orderbyDesc('id')->paginate(10);
        $unreadNotificationsCount = AdminNotification::where('status', 0)->get()->count();
        return view('backend.notifications.index', ['notifications' => $notifications, 'unreadNotificationsCount' => $unreadNotificationsCount]);
    }

    public function view($id)
    {
        $notification = AdminNotification::where('id', unhashid($id))->firstOrFail();
        $updateStatus = $notification->update(['status' => 1]);
        if ($updateStatus) {
            return redirect($notification->link);
        }
    }

    public function readAll()
    {
        $notifications = AdminNotification::where('status', 0)->get();
        if ($notifications->count() == 0) {
            toastr()->error(admin_lang('No unread notifications available'));
            return back();
        }
        foreach ($notifications as $notification) {
            $notification->update(['status' => 1]);
        }
        toastr()->success(admin_lang('All notifications has been read successfully'));
        return back();
    }

    public function deleteAllRead()
    {
        $notifications = AdminNotification::where('status', 1)->get();
        if ($notifications->count() == 0) {
            toastr()->error(admin_lang('No read notifications available'));
            return back();
        }
        foreach ($notifications as $notification) {
            $notification->delete();
        }
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
