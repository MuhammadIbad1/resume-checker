<?php

namespace App\Filament\Resources\RuleSettingResource\Pages;

use App\Filament\Resources\RuleSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRuleSettings extends ListRecords
{
    protected static string $resource = RuleSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
