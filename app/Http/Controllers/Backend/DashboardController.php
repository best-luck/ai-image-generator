<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneratedImage;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $widget['total_earnings'] = Transaction::paid()->sum('total');
        $widget['total_users'] = User::all()->count();
        $widget['total_generated_images'] = GeneratedImage::notExpired()->count();
        $widget['current_month_earnings'] = Transaction::whereMonth('created_at', Carbon::now()->month)->paid()->sum('total');
        $widget['current_month_users'] = User::whereMonth('created_at', Carbon::now()->month)->count();
        $widget['current_month_generated_images'] = GeneratedImage::whereMonth('created_at', Carbon::now()->month)->notExpired()->count();
        $transactions = Transaction::paid()->orderbyDesc('id')->limit(6)->get();
        $users = User::orderbyDesc('id')->limit(6)->get();
        $countUsersLogs = UserLog::where('created_at', '>=', Carbon::now()->startOfMonth())->get()->count();
        return view('backend.dashboard.index', [
            'widget' => $widget,
            'transactions' => $transactions,
            'users' => $users,
            'countUsersLogs' => $countUsersLogs,
        ]);
    }

    public function earningsChartData()
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();
        $dates = chartDates($startDate, $endDate);
        $getWeekEarnings = Transaction::paid()->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->selectRaw('DATE(created_at) as date, SUM(total) as sum')
            ->groupBy('date')
            ->pluck('sum', 'date');
        $getEarningsData = $dates->merge($getWeekEarnings);
        $earningsChartLabels = [];
        $earningsChartData = [];
        foreach ($getEarningsData as $key => $value) {
            $earningsChartLabels[] = Carbon::parse($key)->format('d M');
            $earningsChartData[] = $value;
        }
        $suggestedMax = (max($earningsChartData) > 9) ? max($earningsChartData) + 2 : 10;
        return ['earningsChartLabels' => $earningsChartLabels, 'earningsChartData' => $earningsChartData, 'suggestedMax' => $suggestedMax];
    }

    public function usersChartData()
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();
        $dates = chartDates($startDate, $endDate);
        $usersRecord = User::where('created_at', '>=', Carbon::now()->startOfWeek())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');
        $usersRecordData = $dates->merge($usersRecord);
        $usersChartLabels = [];
        $usersChartData = [];
        foreach ($usersRecordData as $key => $value) {
            $usersChartLabels[] = Carbon::parse($key)->format('d F');
            $usersChartData[] = $value;
        }
        $suggestedMax = (max($usersChartData) > 9) ? max($usersChartData) + 2 : 10;
        return ['usersChartLabels' => $usersChartLabels, 'usersChartData' => $usersChartData, 'suggestedMax' => $suggestedMax];
    }

    public function logsChartData()
    {
        $usersLogs = UserLog::where('created_at', '>=', Carbon::now()->startOfMonth())->get(['browser', 'os', 'country']);
        $browserChartData = $usersLogs->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $osChartData = $usersLogs->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $countryChartData = $usersLogs->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        });
        return [
            'browsers' => ['keys' => $browserChartData->keys(), 'values' => $browserChartData->flatten()],
            'os' => ['keys' => $osChartData->keys(), 'values' => $osChartData->flatten()],
            'countries' => ['keys' => $countryChartData->keys(), 'values' => $countryChartData->flatten()],
        ];
    }
}