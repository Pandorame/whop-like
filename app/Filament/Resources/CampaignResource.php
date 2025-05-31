<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                RichEditor::make('description')->required(),
                TextInput::make('budget')
                    ->numeric()
                    ->required()
                    ->prefix('$'),
                TextInput::make('payout_structure')->required(),
                Select::make('content_type')
                    ->options([
                        'UGC' => 'User Generated Content',
                        'Professional' => 'Professional Content',
                    ])
                    ->required(),
                TagsInput::make('platforms')
                    ->suggestions([
                        'youtube', 'instagram', 'tiktok', 'twitter'
                    ]),
                Toggle::make('is_active')->default(true),
                Select::make('advertiser_id')
                    ->relationship('advertiser', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('budget')->money('USD'),
                Tables\Columns\TextColumn::make('amount_paid')->money('USD'),
                Tables\Columns\TextColumn::make('content_type'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('content_type')
                    ->options([
                        'UGC' => 'User Generated Content',
                        'Professional' => 'Professional Content',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
