<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SubmissionsController extends Controller
{
    public function submissions()
    {

        $response = Http::get(env('API_URL') . '/api/submissions');

        if ($response->failed()) {
            return back()->withErrors(['api' => 'Не вдалося отримати дані з API']);
        }

        $submissions = collect($response->json('data'));

        $columns = [
            'participant' => 'ПІБ учасника',
            'language' => 'Мова програмування',
            'score' => 'Кількість балів',
            'submitted_at' => 'Дата подання'
        ];

        $rows = $submissions->map(function ($s) {
            return [
                'id' => $s['id'],
                'participant' => $s['participant'] ?? 'Інформація відсутня',
                'language' => $s['language'] ?? 'Інформація відсутня',
                'score' => $s['score'] ?? 'Інформація відсутня',
                'submitted_at' => $s['submitted_at'] ?? 'Інформація відсутня',
            ];
        });

        return view('universal-pages.table-page', [
            'title' => 'Перелік спроб учасників',
            'columns' => $columns,
            'rows' => $rows,
            'filters' => FilterController::getSubmisionFilters(),
            'routeName' => 'submissions.moreDetails',
            'pageType' => 'submissions'
        ]);
    }

    public function showSubmission($id)
    {
        $response = Http::get(env('API_URL') . "/api/submissions/{$id}");

        if ($response->failed() || empty($response->json('data'))) {
            abort(404, 'Спроба не знайдена');
        }

        $submission = $response->json('data');

        $info = [
            'ПІБ учасника' => $submission['participant_full_name'],
            'Мова програмування' => $submission['submission_language'],
            'Кількість балів' => $submission['submission_score'],
            'Дата подання' => $submission['submission_submitted_at'],
        ];

        return view('universal-pages.form-page', [
            'title' => 'Детальна інформація про спробу',
            'info' => $info,
            'code' => $submission['submission_code'],
            'test_name' => $submission['test_name'],
            'test_description' => $submission['test_description'],
            'verdict_code' => $submission['verdict_code'],
        ]);
    }


}
