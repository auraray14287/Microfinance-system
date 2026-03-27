<?php
namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\MemberDeposit;

class CreateLoan extends CreateRecord
{
    protected $listeners = ['loan-products-updated' => 'handleProductsUpdated'];

    public function handleProductsUpdated(float $principal, string $description): void
    {
        $this->data['principal_calc']       = $principal;
        $this->data['principal_amount']     = $principal;
        $this->data['product_description']  = $description;
    }
    protected static string $resource = LoanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['loan_number'])) {
            $data['loan_number'] = IdGenerator::generate(['table' => 'loans', 'field' => 'loan_number', 'length' => 10, 'prefix' => 'LN-']);
        }

        if (empty($data['balance'])) {
            $data['balance'] = $data['repayment_amount'] ?? 0;
        }

        unset($data['reference_price']);
        unset($data['duration_error']);
        unset($data['member_id_number']);
        unset($data['products_livewire']);
        unset($data['principal_calc']);
        $data['created_by'] = auth()->id();
        $data['loan_status'] = 'requested';
        return $data;
    }

    protected function afterCreate(): void
    {
        $loan = $this->record;

        // Only process deposit for LONG TERM (5) and SPECIAL CATEGORY (6)
        if (!in_array($loan->loan_type_id, [5, 6])) return;

        $requiredDeposit = (float)($loan->deposit ?? 0);
        if ($requiredDeposit <= 0) return;

        $memberId       = $loan->member_id;
        $depositBalance = MemberDeposit::getBalance($memberId);

        if ($depositBalance >= $requiredDeposit) {
            // Full deposit available — debit and approve
            MemberDeposit::debit($memberId, $requiredDeposit,
                'Deposit applied to loan ' . $loan->loan_number, $loan->id);
            $loan->loan_status = 'approved';
            $loan->save();
            Notification::make()->success()
                ->title('Loan approved')
                ->body('Deposit sufficient. Loan automatically approved.')
                ->send();
        } else {
            // Partial deposit — debit what is available, set pending
            if ($depositBalance > 0) {
                MemberDeposit::debit($memberId, $depositBalance,
                    'Partial deposit applied to loan ' . $loan->loan_number, $loan->id);
            }
            $loan->loan_status = 'pending';
            $loan->save();
            $shortfall = $requiredDeposit - $depositBalance;
            Notification::make()->warning()
                ->title('Loan pending — deposit insufficient')
                ->body('Required: KES ' . number_format($requiredDeposit, 2) .
                       '. Shortfall: KES ' . number_format($shortfall, 2) .
                       '. Top up on Deposit page to auto-approve.')
                ->send();
        }
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Loan created')
            ->body('The Loan has been created successfully.');
    }
}
