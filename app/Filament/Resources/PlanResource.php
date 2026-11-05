<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'SaaS Subscription Engine';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price_monthly')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\TextInput::make('price_yearly')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\TextInput::make('price_pkr_monthly')
                    ->numeric()
                    ->prefix('Rs.')
                    ->label('Monthly PKR Price'),
                Forms\Components\TextInput::make('price_pkr_yearly')
                    ->numeric()
                    ->prefix('Rs.')
                    ->label('Yearly PKR Price'),
                Forms\Components\TextInput::make('scan_limit')
                    ->numeric()
                    ->helperText('-1 for Unlimited scans')
                    ->required(),
                Forms\Components\TextInput::make('jd_limit')
                    ->numeric()
                    ->helperText('-1 for Unlimited JD keyword matches')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\Toggle::make('is_popular')
                    ->label('Featured Popular Badge'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_monthly')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_pkr_monthly')
                    ->label('PKR Price')
                    ->prefix('Rs. '),
                Tables\Columns\TextColumn::make('scan_limit')
                    ->formatStateUsing(fn ($state) => $state === -1 ? 'Unlimited' : $state),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([])
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
