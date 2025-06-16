<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TeachersController extends Controller
{
    public function teachers()
    {
        $response = Http::get(env('API_URL') . '/api/teachers');

        if ($response->failed()) {
            return back()->withErrors(['api' => 'Не вдалося отримати дані з API']);
        }

        $teachers = collect($response->json('data'));

        $columns = [
            'name' => 'ПІБ педагогічного працівника',
            'school' => 'Освітній заклад',
        ];

        $rows = $teachers->map(function ($t) {
            return [
                'id' => $t['id'],
                'name' => $t['name'] ?? 'Інформація відсутня',
                'school' => $t['school'] ?? 'Інформація відсутня'
            ];
        });

        return view('universal-pages.table-page', [
            'title' => 'Перелік вчителів, що супроводжують учасників олімпіади',
            'columns' => $columns,
            'filters' => FilterController::getTeacherFilters(),
            'pageType' => 'teachers',
            'rows' => $rows,
            'routeName' => 'teachers.moreDetails'
        ]);
    }

    public function showTeacher($id)
    {
        $response = Http::get(env('API_URL') . "/api/teachers/{$id}");

        if ($response->failed() || empty($response->json('data'))) {
            abort(404, 'Викладач не знайдений');
        }

        $data = $response->json('data');

        // Якщо взагалі немає записів — це означає, що й викладача немає
        if (empty($data)) {
            abort(404, 'Викладач не знайдений');
        }

        $teacher = $data[0];

        $info = [
            'ПІБ викладача' => $teacher['teacher_full_name'] ?? '—',
            'Навчальний заклад' => $teacher['teacher_school'] ?? '—',
        ];

        $columns = [
            'participant_full_name' => 'ПІБ учасника',
            'participant_class' => 'Клас',
            'submission_language' => 'Мова',
            'submission_score' => 'Бали',
            'submission_submitted_at' => 'Дата'
        ];

        // Фільтруємо лише ті рядки, які мають submission_id
        $rows = collect($data)->filter(function ($row) {
            return isset($row['submission_id']);
        })->map(function ($row) {
            return [
                'participant_full_name' => $row['participant_full_name'] ?? '—',
                'participant_class' => $row['participant_class'] ?? '—',
                'submission_language' => $row['submission_language'] ?? '—',
                'submission_score' => $row['submission_score'] ?? '—',
                'submission_submitted_at' => $row['submission_submitted_at'] ?? '—',
                'href' => route('submissions.moreDetails', [
                    'id' => $row['submission_id'],
                    'backUrl' => url()->full()
                ])
            ];
        });

        return view('universal-pages.form-page', [
            'title' => 'Історія спроб учнів викладача',
            'info' => $info,
            'table' => $rows->isNotEmpty()
                ? [
                    'columns' => $columns,
                    'rows' => $rows
                  ]
                : null // Якщо немає рядків, таблиця не передається
        ]);
    }
}
