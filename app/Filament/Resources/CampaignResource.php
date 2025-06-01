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

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;

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

   // Payout Structure Section
   Select::make('payout_structure_type')
   ->label('Payout Type')
   ->options([
       'per_view' => 'Per View',
       'percentage' => 'Percentage of Budget',
       'fixed' => 'Fixed Amount',
   ])
   ->reactive()
   ->required()
   ->afterStateUpdated(function ($state, $set) {
       // Reset dependent fields when type changes
       $set('payout_amount', null);
       $set('payout_threshold', null);
   }),
   
TextInput::make('payout_amount')
   ->numeric()
   ->label('Payout Amount')
   ->required()
   ->prefix('$')
   ->minValue(0.01),
   
TextInput::make('payout_threshold')
   ->numeric()
   ->label('Views Threshold (for per view)')
   ->requiredIf('payout_structure_type', 'per_view')
   ->hidden(fn ($get) => $get('payout_structure_type') !== 'per_view')
   ->minValue(1),
   
TextInput::make('payout_structure_display')
   ->label('Payout Structure Summary')
   ->disabled()
   ->dehydrated(false)
   ->formatStateUsing(function ($get) {
       $type = $get('payout_structure_type');
       $amount = $get('payout_amount');
       $threshold = $get('payout_threshold');
       
       if (!$type || !$amount) return '';
       
       return match($type) {
           'per_view' => "{$threshold} views / \${$amount}",
           'percentage' => "{$amount}% of budget",
           'fixed' => "Fixed \${$amount} per submission",
           default => ''
       };
   }),
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
