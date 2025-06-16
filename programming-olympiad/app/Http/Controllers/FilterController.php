<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    public static function getParticipantFilters()
    {
        return [
            [
                'name' => 'full_name',
                'label' => 'ПІБ учасника',
                'type' => 'text',
                'value' => request('full_name'),
            ],
            [
                'name' => 'school',
                'label' => 'Освітній заклад',
                'type' => 'text',
                'value' => request('school'),
            ],
            [
                'name' => 'class',
                'label' => 'Клас',
                'type' => 'text',
                'value' => request('class'),
            ],
            [
                'name' => 'teacher_full_name',
                'label' => 'ПІБ викладача',
                'type' => 'text',
                'value' => request('teacher_full_name'),
            ]
        ];
    }

    public static function getTeacherFilters()
    {
        return [
            [
                'name' => 'full_name',
                'label' => 'ПІБ викладача',
                'type' => 'text',
                'value' => request('full_name'),
            ],
            [
                'name' => 'school',
                'label' => 'Освітній заклад',
                'type' => 'text',
                'value' => request('school'),
            ],
        ];
    }

    public static function getSubmisionFilters()
    {
        $response = Http::get(env('API_URL') . '/api/languages');

        if ($response->failed()) {
            return back()->withErrors(['api' => 'Не вдалося отримати список мов з API']);
        }

        $languages = collect($response->json('data'));

        $languageOptions = [];
        foreach ($languages as $lang) {
            $languageOptions[$lang['language']] = $lang['language'];
        }

        return [
            [
                'name' => 'full_name',
                'label' => 'ПІБ учасника',
                'type' => 'text',
                'value' => request('full_name'),
            ],
            [
                'name' => 'language',
                'label' => 'Мови програмування',
                'type' => 'select',
                'options' => $languageOptions,
                'selected' => request('language'),
            ],
            [
                'name' => 'score_min',
                'label' => 'Мінімум балів',
                'type' => 'number',
                'value' => request('score_min'),
            ],
            [
                'name' => 'score_max',
                'label' => 'Максимум балів',
                'type' => 'number',
                'value' => request('score_max'),
            ],
            [
                'name' => 'date_from',
                'label' => 'Дата від',
                'type' => 'date',
                'value' => request('date_from'),
            ],
            [
                'name' => 'date_to',
                'label' => 'Дата до',
                'type' => 'date',
                'value' => request('date_to'),
            ],
            [
                'name' => 'has_score',
                'label' => 'Наявність балів',
                'type' => 'select',
                'options' => ['yes' => 'Є бали', 'no' => 'Без балів'],
                'selected' => request('has_score'),
            ],
        ];
    }

    public function filter(Request $request, $pageType)
    {
        $data = [];

        switch ($pageType) {
            case 'submissions':
                $queryParams = $request->only([
                    'full_name',
                    'language',
                    'score_min',
                    'score_max',
                    'date_from',
                    'date_to',
                    'teacher_id',
                    'class',
                    'school',
                    'has_score'
                ]);

                $data = Http::get(env('API_URL') . "/api/filter/{$pageType}", $queryParams);
                break;
            case 'participants':
                $queryParams = $request->only([
                    'full_name',
                    'school',
                    'class',
                    'teacher_full_name'
                ]);

                $data = Http::get(env('API_URL') . "/api/filter/{$pageType}", $queryParams);
                break;
            case 'teachers':
                $queryParams = $request->only([
                    'full_name',
                    'school',
                ]);

                $data = Http::get(env('API_URL') . "/api/filter/{$pageType}", $queryParams);
                break;

            default:
                return response()->json(['error' => 'Невідомий тип фільтрації'], 400);
        }

        return response()->json($data->json());
    }
}
