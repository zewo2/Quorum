<?php

namespace App\Http\Controllers;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.admin.index');
    }

    public function users()
    {
        return view('dashboards.admin.users');
    }

    public function courses()
    {
        return view('dashboards.admin.courses');
    }

    public function timetables()
    {
        return view('dashboards.admin.timetables');
    }
}
