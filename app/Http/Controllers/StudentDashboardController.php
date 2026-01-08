<?php

namespace App\Http\Controllers;

class StudentDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.student.index');
    }

    public function schedule()
    {
        return view('dashboards.student.schedule');
    }

    public function subjects()
    {
        return view('dashboards.student.subjects');
    }

    public function grades()
    {
        return view('dashboards.student.grades');
    }

    public function exams()
    {
        return view('dashboards.student.exams');
    }
}
