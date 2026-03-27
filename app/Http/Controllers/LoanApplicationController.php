<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanApplicationController extends Controller
{
    public function preview($id)
    {
        $loan     = Loan::with('borrower', 'loan_type', 'member', 'createdBy')->findOrFail($id);
        $borrower = $this->resolveBorrower($loan);
        $user        = $loan->createdBy ?? auth()->user();
        $companyName = $user->name ?? config('app.name');
        $branch      = $user && $user->branch ? $user->branch : null;

        $pdf = Pdf::loadView('pdfs.loan-application', compact('loan', 'borrower', 'companyName', 'branch', 'user'));

        return $pdf->stream("loan-application-{$loan->id}.pdf");
    }

    public function download($id)
    {
        $loan     = Loan::with('borrower', 'loan_type', 'member', 'createdBy')->findOrFail($id);
        $borrower = $this->resolveBorrower($loan);
        $user        = $loan->createdBy ?? auth()->user();
        $companyName = $user->name ?? config('app.name');
        $branch      = $user && $user->branch ? $user->branch : null;

        $pdf = Pdf::loadView('pdfs.loan-application', compact('loan', 'borrower', 'companyName', 'branch', 'user'));

        return $pdf->download("loan-application-{$loan->id}.pdf");
    }

    /**
     * Resolve the person attached to the loan.
     * New loans use member_id; legacy loans use borrower_id.
     * Returns a stdClass with consistent property names so the
     * blade template works regardless of which model is used.
     */
    private function resolveBorrower(Loan $loan): object
    {
        // Prefer the legacy borrower relationship first
        if ($loan->borrower) {
            return $loan->borrower;
        }

        // Fall back to the Member model, mapping fields to borrower-style names
        if ($loan->member) {
            $m = $loan->member;
            $obj = new \stdClass();
            $obj->first_name     = $m->first_name  ?? $m->name ?? '';
            $obj->last_name      = $m->last_name   ?? '';
            $obj->middle_name    = $m->middle_name ?? '';
            $obj->identification = $m->id_number   ?? '';
            $obj->mobile         = $m->mobile_no   ?? $m->phone ?? '';
            $obj->email          = $m->email        ?? '';
            $obj->dob            = $m->dob          ?? null;
            $obj->gender         = $m->gender       ?? '';
            $obj->address        = $m->physical_address ?? $m->address ?? '';
            $obj->city           = $m->town         ?? '';
            $obj->province       = $m->county       ?? '';
            $obj->occupation     = $m->business_name ?? '';
            return $obj;
        }

        // No person found — return empty object so blade ?? '' fallbacks work
        return new \stdClass();
    }
}