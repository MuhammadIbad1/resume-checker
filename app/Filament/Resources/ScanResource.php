<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanResource\Pages;
use App\Models\Scan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScanResource extends Resource
{
    protected static ?string $model = Scan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationGroup = 'ATS Engine Settings';
    protected static ?string $navigationLabel = 'Scan Logs & Audits';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->nullable(),
                Forms\Components\TextInput::make('file_name')
                    ->required(),
                Forms\Components\TextInput::make('overall_score')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('job_description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('raw_text')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->default('Guest'),
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overall_score')
                    ->label('ATS Score')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListScans::route('/'),
        ];
    }
}
