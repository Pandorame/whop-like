<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\UserCampaign;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SummitedCRsResource\Pages;
use App\Filament\Resources\SummitedCRsResource\RelationManagers;

class SummitedCRsResource extends Resource
{
    protected static ?string $model = UserCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                
                Select::make('campaign_id')
                    ->relationship('campaign', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                
                TextInput::make('content_url')
                    ->label('Content URL')
                    ->url()
                    ->required()
                    ->columnSpanFull(),
                
                Select::make('platform')
                    ->options([
                        'youtube' => 'YouTube',
                        'instagram' => 'Instagram',
                        'tiktok' => 'TikTok',
                        'twitter' => 'Twitter',
                        'facebook' => 'Facebook',
                    ])
                    ->required(),
                
                TextInput::make('views')
                    ->numeric()
                    ->minValue(0),
                
                TextInput::make('earnings')
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0),
                
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Creator')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('content_url')
                    ->label('Content')
                    ->limit(30)
                    ->tooltip(fn (UserCampaign $record) => $record->content_url)
                    ->url(fn (UserCampaign $record) => $record->content_url, true),
                
                TextColumn::make('platform')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'youtube' => 'danger',
                        'instagram' => 'pink',
                        'tiktok' => 'gray',
                        'twitter' => 'sky',
                        'facebook' => 'blue',
                        default => 'gray',
                    }),
                
                TextColumn::make('views')
                    ->numeric()
                    ->sortable(),
                
                TextColumn::make('earnings')
                    ->money('USD')
                    ->sortable(),
                
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'paid',
                    ])
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                    ]),
                
                SelectFilter::make('platform')
                    ->options([
                        'youtube' => 'YouTube',
                        'instagram' => 'Instagram',
                        'tiktok' => 'TikTok',
                        'twitter' => 'Twitter',
                        'facebook' => 'Facebook',
                    ]),
                
                SelectFilter::make('campaign_id')
                    ->relationship('campaign', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->action(fn (UserCampaign $record) => $record->update(['status' => 'approved']))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (UserCampaign $record) => $record->status !== 'approved'),
                
                Action::make('reject')
                    ->action(fn (UserCampaign $record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn (UserCampaign $record) => $record->status !== 'rejected'),
                    
                    Action::make('mark_as_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Mark Submission as Paid')
                    ->modalDescription(fn ($record) => "Are you sure you want to mark this submission as paid and add \${$record->earnings} to {$record->user->name}'s wallet?")
                    ->form([
                        TextInput::make('payment_reference')
                            ->label('Payment Reference/Notes')
                            ->required(),
                    ])
                    ->action(function (array $data, UserCampaign $record) {
                        $record->markAsPaid();
                        
                        // Record payment reference
                        $record->update(['payment_reference' => $data['payment_reference']]);
                        
                        Notification::make()
                            ->title('Payment Processed')
                            ->body("Added \${$record->earnings} to {$record->user->name}'s wallet")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (UserCampaign $record): bool => $record->status !== 'paid'),
                
                DeleteAction::make(),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'approved']))
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check'),
                    
                    Tables\Actions\BulkAction::make('reject')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'rejected']))
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-o-x-mark'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSummitedCRs::route('/'),
            'create' => Pages\CreateSummitedCRs::route('/create'),
            'edit' => Pages\EditSummitedCRs::route('/{record}/edit'),
        ];
    }
}
