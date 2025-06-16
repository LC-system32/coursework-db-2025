<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablesController extends Controller
{
    protected $apiBaseUrl = 'http://localhost:3000/tables';

    public function index()
    {
        $response = Http::get(env('API_URL') . '/tables');

        if ($response->failed()) {
            return back()->with('error', 'Не вдалося отримати дані з API.');
        }

        $tables = $response->json();

        return view('tables.select-table', ['tables' => $tables]);
    }


    public function list(string $table)
    {
        $response = Http::get(env('API_URL') . "/tables/{$table}");

        if ($response->failed()) {
            abort(404, 'Не вдалося завантажити дані таблиці: ' . $table);
        }

        $rows = $response->json();

        $columns = !empty($rows) ? array_keys($rows[0]) : [];

        return view('tables.table-page', [
            'title' => 'Список записів таблиці ' . $table,
            'columns' => array_combine($columns, $columns),
            'rows' => $rows,
            'pageType' => $table,
            'routeName' => 'table.list',
        ]);
    }

    public function create(string $table)
    {
        return view('manual-entry.form', [
            'title' => 'Створити запис у таблиці ' . $table,
            'table' => $table,
        ]);
    }

    public function store(Request $request, string $table)
    {
        $response = Http::post("{$this->apiBaseUrl}/{$table}", $request->except('_token'));

        if ($response->failed()) {
            return back()->with('error', 'Не вдалося створити запис.');
        }

        return redirect()->route('table.destroyAll', ['table' => $table])
            ->with('success', 'Запис успішно додано.');
    }
    public function destroy(string $table)
    {
        $response = Http::delete(env('API_URL') . "/tables/{$table}");

        if ($response->failed()) {
            abort(404, 'Не вдалося завантажити дані таблиці: ' . $table);
        }

        return redirect()->route('table.index', ['table' => $table])
            ->with('success', value: 'Таблицю ' . $table . ' видалено');
    }
}
