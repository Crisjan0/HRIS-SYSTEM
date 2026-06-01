<?php

namespace App\Services;

use App\Models\Saln;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class SalnPdfExporter
{
    public function download(Saln $saln): Response
    {
        $familyName = $saln->declarant_info['family_name'] ?? 'SALN';
        $filename = 'SALN_2025_'.preg_replace('/\s+/', '_', $familyName).'_'.$saln->as_of_date->format('Y-m-d').'.pdf';

        return Pdf::loadView('saln.pdf', compact('saln'))
            ->setPaper('legal', 'portrait')
            ->download($filename, ['Content-Type' => 'application/pdf']);
    }
}
