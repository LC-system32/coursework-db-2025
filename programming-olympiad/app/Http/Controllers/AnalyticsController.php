<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('analytics.index');
    }

    public function submissions()
    {
        $response = Http::get('http://localhost:3000/api/analytics/submissions');
        $data = $response->json();

        if (!$response->successful() || !$data['success']) {
            abort(500, 'API error');
        }

        $submissions = (object)$data['general'];
        $languages = $data['languages'];
        $classes = $data['classes'];
        $dynamics = $data['dynamics'];
        $topParticipants = array_map(fn($item) => (object)$item, $data['topParticipants']); // ✅

        return view('analytics.tabs.submissions', [
            'participantsCount' => $submissions->participants_count,
            'attemptsCount'     => $submissions->attempts_count,
            'averageScore'      => $submissions->average_score,
            'maxScore'          => $submissions->max_score,
            'langLabels'        => collect($languages)->pluck('language'),
            'langCounts'        => collect($languages)->pluck('total'),
            'classLabels'       => collect($classes)->pluck('class'),
            'classCounts'       => collect($classes)->pluck('total'),
            'dates'             => collect($dynamics)->pluck('submission_date'),
            'counts'            => collect($dynamics)->pluck('total'),
            'topParticipants'   => $topParticipants,
        ]);
    }

    public function teachers()
    {
        $response = Http::get('http://localhost:3000/api/analytics/teachers');
        $analytics = $response->json();

        if (!$response->successful() || !$analytics['success']) {
            abort(500, 'API error');
        }

        $general = $analytics['general'] ?? [];
        $teachersCount = $general['total_teachers'] ?? 0;
        $avgParticipantsPerTeacher = $general['avg_participants_per_teacher'] ?? 0;
        $averageScore = $analytics['averageScore'] ?? 0;

        $topTeachers = collect($analytics['topTeachers'] ?? []);
        $bestTeacher = $analytics['bestTeacher'] ?? null;

        $activityOverTime = collect($analytics['activityOverTime'] ?? [])
            ->groupBy('date')
            ->map(function ($entries) {
                return $entries->sum('submission_count');
            });

        $minScores = collect($analytics['minScores'] ?? [])
            ->pluck('min_score', 'full_name');

        return view('analytics.tabs.teachers', compact(
            'teachersCount',
            'avgParticipantsPerTeacher',
            'averageScore',
            'topTeachers',
            'bestTeacher',
            'activityOverTime',
            'minScores'
        ));
    }

    public function participants()
    {
        $response = Http::get('http://localhost:3000/api/analytics/participants');
        $data = $response->json();

        if (!$response->successful() || !$data['success']) {
            abort(500, 'API error');
        }

        $totalParticipants = $data['general']['total_participants'] ?? 0;
        $topByAvg = collect($data['topByAvg'] ?? []);
        $topByMax = collect($data['topByMax'] ?? []);
        $popularLang = $data['mostPopularLanguage']['language'] ?? '-';
        $popularLangCount = $data['mostPopularLanguage']['uses'] ?? 0;

        return view('analytics.tabs.participants', compact(
            'totalParticipants',
            'topByAvg',
            'topByMax',
            'popularLang',
            'popularLangCount'
        ));
    }

    public function tests()
    {
        $response = Http::get('http://localhost:3000/api/analytics/tests');
        $data = $response->json();

        if (!$response->successful() || !$data['success']) {
            abort(500, 'API error');
        }

        return view('analytics.tabs.tests', [
            'highScoreLabels'     => collect($data['highScorePercent'])->pluck('test_name'),
            'highScoreData'       => collect($data['highScorePercent'])->pluck('percent_high_scores'),

            'leastAttemptedLabels' => collect($data['leastAttemptedTests'])->pluck('test_name'),
            'leastAttemptedData'   => collect($data['leastAttemptedTests'])->pluck('attempts'),

            'dailyLabels'         => collect($data['dailyActivity'])->pluck('submitted_at'),
            'dailyData'           => collect($data['dailyActivity'])->pluck('submissions_count'),

            'langPerTestLabels'   => collect($data['languagesPerTest'])->pluck('test_name'),
            'langPerTestData'     => collect($data['languagesPerTest'])->pluck('used_languages'),

            'failRateLabels'      => collect($data['failRateByVerdict'])->pluck('test_name'),
            'failRateData'        => collect($data['failRateByVerdict'])->pluck('fail_rate_percent'),
        ]);
    }
}
