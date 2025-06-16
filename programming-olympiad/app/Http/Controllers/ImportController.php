<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function import()
    {
        return view('import.index');
    }

    public function importFile(Request $request, string $type)
    {
        $file = $request->file('json_file');

        if (!$file) {
            return back()->with('error', 'Файл не завантажено.');
        }

        if (!in_array($type, ['participants', 'teachers', 'submissions'])) {
            return back()->with('error', 'Невідомий тип імпорту.');
        }

        try {
            $tempPath = $file->storeAs('temp_uploads', $file->getClientOriginalName());

            $response = Http::attach(
                'json_file',
                Storage::get($tempPath),
                $file->getClientOriginalName()
            )->post(env('API_URL') . "/api/import/$type");

            Storage::delete($tempPath);

            if ($response->successful()) {
                return back()->with('success', $response->json('message'));
            }

            return back()->with('error', $response->json('message') ?? 'Помилка імпорту.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Помилка: ' . $e->getMessage());
        }
    }
}
