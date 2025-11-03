<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Registrations (dari tabel toeic_registration)
        $totalRegistrations = DB::table('toeic_registration')->count();

        // Paid Status (status = 'paid')
        $paidRegistrations = DB::table('toeic_registration')
            ->where('status', 'paid')
            ->count();

        // Certificates Uploaded (menggunakan field certificate_path, bukan ktp_path)
        $certUploaded = DB::table('toeic_scores')
            ->whereNotNull('pdf')
            ->count();

        // Scores (jumlah yang memiliki skor)
        $scores = DB::table('toeic_scores')
            ->whereNotNull('score')
            ->where('score', '!=', 0)
            ->count();

        // Majors
        $majors = DB::table('majors')->count();

        // Study Programs
        $studyPrograms = DB::table('study_programs')->count();

        // Registrations for chart (per bulan)
        $monthlyData = DB::table('toeic_registration')
            ->selectRaw('MONTH(registration_date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Buat array lengkap 12 bulan
        $monthlyCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyCounts[] = $monthlyData[$i] ?? 0;
        }

        return view('dashboard.index', compact(
            'totalRegistrations',
            'paidRegistrations',
            'certUploaded',
            'monthlyCounts',
            'scores',
            'majors',
            'studyPrograms'
        ));
    }
}
