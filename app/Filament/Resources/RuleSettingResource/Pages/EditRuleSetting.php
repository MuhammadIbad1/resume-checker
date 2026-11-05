<?php

namespace App\Filament\Resources\RuleSettingResource\Pages;

use App\Filament\Resources\RuleSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRuleSetting extends EditRecord
{
    protected static string $resource = RuleSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
