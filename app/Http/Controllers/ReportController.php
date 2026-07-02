<?php

namespace App\Http\Controllers;

use App\Services\CertificateService;
use App\Services\StudentReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function __construct(
        protected StudentReportService $reports,
        protected CertificateService $certificates,
    ) {}

    public function download(Request $request): Response
    {
        $pdf = $this->reports->pdfFor($request->user());
        $filename = 'arivexa-report-'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }

    public function certificate(Request $request): Response
    {
        $pdf = $this->certificates->pdfFor($request->user());
        $filename = 'arivexa-certificate-'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }
}
