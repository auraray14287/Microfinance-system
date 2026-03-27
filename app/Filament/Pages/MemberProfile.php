<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Member;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberProfile extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'Member Management';
    protected static ?string $navigationLabel = 'Member Profile';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.member-profile';

    public ?string $id_number = null;
    public ?Member $member = null;

    public function form(Form $form): Form
    {
        return $form->schema([
            Components\TextInput::make('id_number')
                ->label('Enter Member ID Number')
                ->placeholder('Type ID number and press Enter or click Search')
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state) {
                    if ($state) {
                        $user = auth()->user();
                        $query = \App\Models\Member::with(['groups', 'savings', 'loans'])
                            ->where('id_number', $state);

                        if (!$user->hasRole('super_admin')) {
                            $groupIds = \App\Models\Group::where('assigned_officer', $user->id)->pluck('id');
                            $query->whereIn('group_id', $groupIds);
                        }

                        $this->member = $query->first();
                    } else {
                        $this->member = null;
                    }
                }),
        ]);
    }
    
    public function cancelAction(): Action
    {
        return Action::make('cancel')
            ->label('Cancel')
            ->color('gray')
            ->action(function () {
                $this->id_number = null;
                $this->member = null;
                $this->form->fill();
            });
    }

    public function downloadPdfAction(): Action
    {
        return Action::make('downloadPdf')
            ->label('Download PDF')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function () {
                $member = $this->member;
                $pdf = Pdf::loadView('filament.pages.member-profile-pdf', compact('member'));
                return response()->streamDownload(
                    fn () => print($pdf->output()),
                    "member-profile-{$member->id_number}.pdf"
                );
            });
    }

    public function editMemberAction(): Action
    {
        return Action::make("editMember")
            ->label("Edit Member")
            ->icon("heroicon-o-pencil-square")
            ->color("warning")
            ->visible(fn () => auth()->user()->hasRole("super_admin") || auth()->user()->hasRole("admin"))
            ->url(fn () => $this->member ? route("filament.admin.resources.members.edit", ["record" => $this->member->id]) : null);
    }

    public function transferMemberAction(): Action
    {
        return Action::make("transferMember")
            ->label("Member Transfer")
            ->icon("heroicon-o-arrows-right-left")
            ->color("info")
            ->visible(fn () => auth()->user()->hasRole("super_admin") || auth()->user()->hasRole("admin"))
            ->url(fn () => $this->member ? route("member.transfer.show", ["id" => $this->member->id]) : null);
    }
}