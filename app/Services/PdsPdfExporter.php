<?php

namespace App\Services;

use App\Models\Employee;
use App\Services\Pds\Cs212PdfGenerator;
use Illuminate\Http\Response;
use RuntimeException;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdsPdfExporter
{
    public function __construct(
        private readonly Cs212PdfGenerator $generator
    ) {}

    public function download(Employee $employee): Response
    {
        if (! class_exists(Fpdi::class)) {
            throw new RuntimeException(
                'PDF library not installed. Run: composer require setasign/fpdi-tcpdf'
            );
        }

        $template = resource_path('pdf/cs_form_212_template.pdf');
        $pngFallback = public_path('images/pds/pds-page-1.png');

        if (! file_exists($template) && ! file_exists($pngFallback)) {
            throw new RuntimeException(
                'CS Form 212 template is missing. Add resources/pdf/cs_form_212_template.pdf or public/images/pds/pds-page-*.png.'
            );
        }

        return $this->generator->download($employee);
    }
}
