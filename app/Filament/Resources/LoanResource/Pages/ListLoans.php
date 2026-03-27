<?php
namespace App\Filament\Resources\LoanResource\Pages;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LoanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Carbon\Carbon;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $overdueThreshold = Carbon::now()->subDays(7)->toDateString();
        return [
            'Active' => Tab::make('Active')
                ->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('loan_status', ['approved', 'partially_paid'])
                    ->where(function ($q) use ($overdueThreshold) {
                        $q->whereNull('next_payment_date')
                          ->orWhere('next_payment_date', '>=', $overdueThreshold);
                    })),

            'Pending' => Tab::make('Pending')
                ->icon('heroicon-m-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('loan_status', ['pending', 'requested'])),

            'Settled' => Tab::make('Settled')
                ->icon('heroicon-m-banknotes')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('loan_status', 'fully_paid')),

            'Overdue' => Tab::make('Overdue')
                ->icon('heroicon-m-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('loan_status', ['approved', 'partially_paid'])
                    ->where('next_payment_date', '<', $overdueThreshold)
                    ->where('balance', '>', 0)),

            'Defaulted' => Tab::make('Defaulted')
                ->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('loan_status', 'defaulted')),
        ];
    }
}
