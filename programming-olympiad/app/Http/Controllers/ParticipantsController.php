<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ParticipantsController extends Controller
{

    public function participants()
    {
        $response = Http::get(env('API_URL') . '/api/participants');

        if ($response->failed()) {
            return back()->withErrors(['api' => 'Не вдалося отримати дані з API']);
        }

        $participants = collect($response->json('data'));

        $columns = [
            'name' => 'ПІБ',
            'school' => 'Освітній заклад',
            'class' => 'Клас',
            'teacher' => 'Викладач'
        ];

        $rows = $participants->map(function ($p) {
            return [
                'id' => $p['id'],
                'name' => $p['name'] ?? 'Інформація відсутня',
                'school' => $p['school'] ?? 'Інформація відсутня',
                'class' => $p['class'] ?? 'Інформація відсутня',
                'teacher' => $p['teacher'] ?? 'Інформація відсутня'
            ];
        });


        return view('universal-pages.table-page', [
            'title' => 'Перелік учасників олімпіади',
            'columns' => $columns,
            'pageType' => 'participants',
            'rows' => $rows,
            'filters' => FilterController::getParticipantFilters(),
            'routeName' => 'participants.moreDetails'
        ]);
    }
    public function showParticipant($id)
    {
        $response = Http::get(env('API_URL') . "/api/participants/{$id}");

        if ($response->failed() || empty($response->json('data'))) {
            abort(404, 'Учасник не знайдений');
        }

        $data = $response->json('data');
        $participant = collect($data)->firstWhere('participant_name', '!=', null);

        if (!$participant) {
            abort(404, 'Учасник не знайдений');
        }

        $info = [
            'ПІБ' => $participant['participant_name'],
            'Освітній заклад' => $participant['school'],
            'Клас' => $participant['class'],
            'Викладач' => $participant['teacher_name'] ?? '—',
        ];

        $columns = [
            'test_name' => 'Назва завдання',
            'language' => 'Мова',
            'score' => 'Кількість балів',
            'submitted_at' => 'Дата подання',
            'verdict' => 'Вердикт'
        ];

        $rows = collect($data)->filter(function ($row) {
            return !is_null($row['submission_id']);
        })->map(function ($row) {
            return [
                'test_name' => $row['test_name'] ?? '—',
                'language' => $row['language'] ?? '—',
                'score' => $row['score'] ?? '—',
                'submitted_at' => $row['submitted_at'] ?? '—',
                'verdict' => $row['verdict_code'] ?? '—',
                'href' => route('submissions.moreDetails', [
                    'id' => $row['submission_id'],
                    'backUrl' => url()->full()
                ])
            ];
        });

        return view('universal-pages.form-page', [
            'title' => 'Детальна інформація про учасника та його спроби',
            'info' => $info,
            'table' => [
                'columns' => $columns,
                'rows' => $rows
            ]
        ]);
    }

}