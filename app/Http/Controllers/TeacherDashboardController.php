<?php

namespace App\Http\Controllers;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.teacher.index');
    }

    public function classes()
    {
        return view('dashboards.teacher.classes');
    }

    public function attendance()
    {
        return view('dashboards.teacher.attendance');
    }
}
