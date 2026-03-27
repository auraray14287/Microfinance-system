<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Group;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $group = Group::find($data['group_id']);
        if ($group) {
            $user = \App\Models\User::find($group->assigned_officer);
            $data['assigned_officer_name'] = $user?->name ?? 'Not Assigned';
        }
        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->groups()->sync([$this->record->group_id]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}