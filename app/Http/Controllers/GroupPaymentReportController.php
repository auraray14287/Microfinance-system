<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Saving;
use App\Models\Repayments;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GroupPaymentReportController extends Controller
{
    public function download(Request $request, $group)
    {
        $date = $request->query('date', now()->format('Y-m-d'));

        /** @var Group $groupModel */
        $groupModel = Group::with([
            'members' => function ($q) {
                $q->where('status', 'active')
                  ->with(['loans' => function ($lq) {
                      $lq->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted', 'fully_paid']);
                  }]);
            }
        ])->findOrFail($group);

        $user    = auth()->user();
        $orgName = $user->name ?? config('app.name');
        $orgAddress = optional($user->branch)->address ?? 'Kenya';
        $officer = $user->name;

        // Build rows from group members
        $rows = [];
        $hasMultiLoanMembers = false;

        foreach ($groupModel->members as $member) {
            $activeLoans = $member->loans->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
            $expectedTotal = $activeLoans->sum(fn($l) => (float)($l->amount_per_installment ?? 0));

            $loanDetails = $activeLoans->map(fn($l) => [
                'id'                     => $l->id,
                'loan_number'            => $l->loan_number ?? '#' . $l->id,
                'amount_per_installment' => (float)($l->amount_per_installment ?? 0),
                'balance'                => (float)($l->balance ?? 0),
                'loan_status'            => $l->loan_status,
            ])->values()->toArray();

            if (count($loanDetails) > 1) {
                $hasMultiLoanMembers = true;
            }

            // Get savings recorded on this date for this member in this session
            $ref = 'GPS-' . $groupModel->id . '-' . Carbon::parse($date)->format('Ymd');

            $savingsAmount = Saving::where('member_id', $member->id)
                ->where('group_id', $groupModel->id)
                ->where('contribution_date', $date)
                ->where('notes', 'like', '%' . $ref . '%')
                ->sum('amount');

            // Get loan payments recorded in this session
            $loanPayment = 0;
            foreach ($loanDetails as $ld) {
                $paid = Repayments::where('loan_id', $ld['id'])
                    ->where('reference_number', 'like', '%' . $ref . '%')
                    ->sum('payments');
                $loanPayment += $paid;
            }

            $rows[] = [
                'member_id'       => $member->id,
                'name'            => trim("{$member->first_name} {$member->middle_name} {$member->last_name}"),
                'id_number'       => $member->id_number,
                'group_name'      => $groupModel->name,
                'savings_input'   => $savingsAmount,
                'loan_input'      => $loanPayment,
                'expected_loan'   => $expectedTotal,
                'loan_details'    => $loanDetails,
                'has_active_loans'=> count($loanDetails) > 0,
            ];
        }

        $totalSavings      = collect($rows)->sum('savings_input');
        $totalLoanPayments = collect($rows)->sum('loan_input');
        $totalExpected     = collect($rows)->sum('expected_loan');
        $savingsCount      = collect($rows)->where('savings_input', '>', 0)->count();
        $loanPaymentsCount = collect($rows)->where('loan_input', '>', 0)->count();

        $group = $groupModel; // blade uses $group

        $pdf = Pdf::loadView('pdfs.group-payment-report', compact(
            'group', 'rows', 'date', 'orgName', 'orgAddress', 'officer',
            'totalSavings', 'totalLoanPayments', 'totalExpected',
            'savingsCount', 'loanPaymentsCount', 'hasMultiLoanMembers'
        ))->setPaper('a4', 'landscape');

        $filename = 'group-payment-' . $groupModel->id . '-' . Carbon::parse($date)->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function blank(Request $request, $group)
    {
        $groupModel = Group::with([
            'members' => function ($q) {
                $q->where('status', 'active')
                  ->with(['loans' => function ($lq) {
                      $lq->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
                  }]);
            }
        ])->findOrFail($group);

        $user    = auth()->user();
        $orgName = $user->name ?? config('app.name');
        $orgAddress = optional($user->branch)->address ?? 'Kenya';
        $officer = $user->name;
        $date = now()->format('Y-m-d');

        $rows = [];
        $hasMultiLoanMembers = false;

        foreach ($groupModel->members as $member) {
            $activeLoans = $member->loans->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
            $expectedTotal = $activeLoans->sum(fn($l) => (float)($l->amount_per_installment ?? 0));
            $loanDetails = $activeLoans->map(fn($l) => [
                'id'                     => $l->id,
                'loan_number'            => $l->loan_number ?? '#' . $l->id,
                'amount_per_installment' => (float)($l->amount_per_installment ?? 0),
                'balance'                => (float)($l->balance ?? 0),
                'loan_status'            => $l->loan_status,
            ])->values()->toArray();

            if (count($loanDetails) > 1) $hasMultiLoanMembers = true;

            $rows[] = [
                'member_id'        => $member->id,
                'name'             => trim("{$member->first_name} {$member->middle_name} {$member->last_name}"),
                'id_number'        => $member->id_number,
                'group_name'       => $groupModel->name,
                'savings_input'    => 0,
                'loan_input'       => 0,
                'expected_loan'    => $expectedTotal,
                'loan_details'     => $loanDetails,
                'has_active_loans' => count($loanDetails) > 0,
            ];
        }

        // Add overdue calculations using shared OverdueCalculator
        foreach ($rows as &$row) {
            $loans = collect($row['loan_details'])->map(fn($ld) => \App\Models\Loan::find($ld['id']))->filter();
            $overdueResult       = \App\Services\OverdueCalculator::forMember($loans);
            $row['overdue']      = $overdueResult['amount'];
            $row['overdue_days'] = $overdueResult['days'];
        }
        unset($row);

        $totalSavings = 0; $totalLoanPayments = 0;
        $totalExpected = collect($rows)->sum('expected_loan');
        $savingsCount = 0; $loanPaymentsCount = 0;
        $group = $groupModel;

        $pdf = Pdf::loadView('pdfs.group-payment-blank', compact(
            'group', 'rows', 'date', 'orgName', 'orgAddress', 'officer',
            'totalSavings', 'totalLoanPayments', 'totalExpected',
            'savingsCount', 'loanPaymentsCount', 'hasMultiLoanMembers'
        ))->setPaper('a4', 'landscape');

        $filename = 'group-payment-blank-' . $groupModel->name . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

}