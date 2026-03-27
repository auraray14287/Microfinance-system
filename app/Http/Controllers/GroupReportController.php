<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Repayments;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GroupReportController extends Controller
{
    /**
     * Show the date-filter form before downloading.
     * GET /admin/groups/{id}/report
     */
    public function preview(Request $request, int $id)
    {
        $group    = Group::with(['members', 'officer'])->findOrFail($id);
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $user     = auth()->user();

        $pdf = $this->buildPdf($group, $user, $dateFrom, $dateTo);

        return $pdf->stream("group-report-{$group->id}.pdf");
    }

    /**
     * Download the PDF.
     * GET /admin/groups/{id}/report/download
     */
    public function download(Request $request, int $id)
    {
        $group    = Group::with(['members', 'officer'])->findOrFail($id);
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $user     = auth()->user();

        $pdf = $this->buildPdf($group, $user, $dateFrom, $dateTo);

        $filename = 'Group-Report-' . str_replace(' ', '-', $group->name) . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function buildPdf(Group $group, $user, ?string $dateFrom, ?string $dateTo): \Barryvdh\DomPDF\PDF
    {
        $pdf = Pdf::loadView('pdfs.group-report', [
            'group'    => $group,
            'user'     => $user,
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => false,
            'defaultFont'          => 'serif',
            'dpi'                  => 150,
        ]);

        return $pdf;
    }
}