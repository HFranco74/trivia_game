<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\File;

class QuestionImportService
{
    public function import($file, $type = 'csv')
    {
        if ($type === 'json') {
            $data = json_decode(File::get($file), true);
            foreach ($data as $item) {
                Question::create($item);
            }
        } elseif ($type === 'csv') {
            // Puedes usar fgetcsv para leer línea por línea
            $handle = fopen($file, "r");
            $header = fgetcsv($handle); // Saltamos cabeceras
            while (($row = fgetcsv($handle)) !== FALSE) {
                Question::create([
                    'category_id' => $row[0],
                    'question_text' => $row[1],
                    // ... mapeo de columnas
                ]);
            }
            fclose($handle);
        }
    }
}