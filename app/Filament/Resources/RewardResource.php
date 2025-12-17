<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RewardResource\Pages;
use App\Filament\Resources\RewardResource\RelationManagers;
use App\Models\Reward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RewardResource extends Resource
{
    protected static ?string $model = Reward::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Reward';
    protected static bool $shouldRegisterNavigation = true;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('shop_url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required(),
                Forms\Components\FileUpload::make('img_url')
                    ->label('Reward Image')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('400')
                    ->imageResizeTargetHeight('400')
                    ->maxSize(2048) // 2MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                    ->disk('public')
                    ->directory('reward-images')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(
                        fn (Get $get): string => 
                            'reward_' . time() . '_' . uniqid() . '.jpg'
                    )
                    ->required(),
                Forms\Components\TextInput::make('stock')
                    ->required(),
                Forms\Components\Toggle::make('is_pinned')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('img_url')
                    ->label('')
                    ->circular()
                    ->height(56)
                    ->width(56),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('stock'),
                Tables\Columns\IconColumn::make('is_pinned')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('order_number')
            ->defaultSort('is_pinned', 'desc')
            ->defaultSort('order_number', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('buy')
                    ->label('Buy')
                    ->icon('heroicon-o-shopping-cart')
                    ->url(fn ($record) => $record->shop_url, true)
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => !isset($record->status) || $record->status != 2),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => !isset($record->status) || $record->status != 2),
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
            'index' => Pages\ListRewards::route('/'),
            'create' => Pages\CreateReward::route('/create'),
            'edit' => Pages\EditReward::route('/{record}/edit'),
        ];
    }
    

}
