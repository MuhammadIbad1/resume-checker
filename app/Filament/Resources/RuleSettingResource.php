<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RuleSettingResource\Pages;
use App\Models\RuleSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RuleSettingResource extends Resource
{
    protected static ?string $model = RuleSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'ATS Engine Settings';
    protected static ?string $navigationLabel = 'Rule Weights & Toggles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rule_code')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('category')
                    ->options([
                        'structure' => 'Structure & Formatting',
                        'contact' => 'Contact & Info',
                        'experience' => 'Experience & Action Verbs',
                        'metrics' => 'Quantifiable Metrics',
                        'keywords' => 'Job Keyword Match',
                        'security' => 'Security & Privacy',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('max_points')
                    ->numeric()
                    ->label('Point Weight (Max Score)')
                    ->required(),
                Forms\Components\Toggle::make('is_enabled')
                    ->label('Rule Active Switch')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rule_code')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'structure',
                        'success' => 'contact',
                        'warning' => 'experience',
                        'info' => 'metrics',
                        'danger' => 'keywords',
                        'gray' => 'security',
                    ]),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_points')
                    ->label('Weight')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_enabled')
                    ->label('Enabled'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'structure' => 'Structure',
                        'contact' => 'Contact',
                        'experience' => 'Experience',
                        'metrics' => 'Metrics',
                        'keywords' => 'Keywords',
                        'security' => 'Security & Privacy',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRuleSettings::route('/'),
            'create' => Pages\CreateRuleSetting::route('/create'),
            'edit' => Pages\EditRuleSetting::route('/{record}/edit'),
        ];
    }
}
