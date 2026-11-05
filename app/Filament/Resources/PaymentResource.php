<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Services\SubscriptionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'SaaS Subscription Engine';
    protected static ?string $navigationLabel = 'Payment Approval Queue';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->required(),
                Forms\Components\TextInput::make('provider')
                    ->required(),
                Forms\Components\TextInput::make('method_details')
                    ->label('Payment Method / Account'),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('currency')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'paid' => 'Approved & Paid',
                        'rejected' => 'Rejected',
                        'refunded' => 'Refunded',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('reference')
                    ->label('Transaction ID / Ref'),
                Forms\Components\FileUpload::make('proof_path')
                    ->label('Payment Proof Screenshot')
                    ->disk('public')
                    ->image()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('user_notes')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('admin_notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('method_details')
                    ->label('Method'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('PKR'),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->label('TRX ID'),
                Tables\Columns\ImageColumn::make('proof_path')
                    ->label('Receipt Proof')
                    ->disk('public'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'paid' => 'Paid',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve Plan')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn (Payment $record) => $record->status === 'pending')
                    ->action(function (Payment $record, SubscriptionService $subService) {
                        if ($record->user && $record->plan) {
                            $subService->activateSubscription(
                                $record->user,
                                $record->plan,
                                $record->provider,
                                'monthly',
                                $record
                            );
                            Notification::make()
                                ->title('Payment Approved & Plan Activated')
                                ->success()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->visible(fn (Payment $record) => $record->status === 'pending')
                    ->action(function (Payment $record, array $data, SubscriptionService $subService) {
                        $subService->rejectPayment($record, auth()->user(), $data['reason']);
                        Notification::make()
                            ->title('Payment Rejected')
                            ->danger()
                            ->send();
                    }),

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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
