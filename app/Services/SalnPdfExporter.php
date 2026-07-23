<?php

namespace App\Services;

use App\Models\Saln;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class SalnPdfExporter
{
    public function download(Saln $saln, bool $inline = false): Response
    {
        $familyName = $saln->declarant_info['family_name'] ?? 'SALN';
        $filename = 'SALN_2025_'.preg_replace('/\s+/', '_', $familyName).'_'.$saln->as_of_date->format('Y-m-d').'.pdf';

        $pdf = Pdf::loadView('saln.pdf', compact('saln'))
            ->setPaper('legal', 'portrait');

        $pdf->render();

        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $fontMetrics = $dompdf->getFontMetrics();
        $font = $fontMetrics->getFont('Times-Roman', 'italic');
        $text = 'Page {PAGE_NUM} of {PAGE_COUNT}';
        $width = $canvas->get_width();
        $height = $canvas->get_height();
        $fontSize = 10;
        $textWidth = $fontMetrics->getTextWidth('Page 00 of 00', $font, $fontSize);
        $footerY = $height - 42;
        $canvas->page_text(($width - $textWidth) / 2, $footerY, $text, $font, $fontSize, [0.45, 0.45, 0.45]);

        if ($inline) {
            return $pdf->stream($filename, ['Content-Type' => 'application/pdf']);
        }

        return $pdf->download($filename, ['Content-Type' => 'application/pdf']);
    }
}
