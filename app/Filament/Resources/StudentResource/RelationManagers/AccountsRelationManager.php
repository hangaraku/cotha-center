<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'accounts';

    protected static ?string $recordTitleAttribute = 'platform_name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('platform_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Platform'),
                Forms\Components\Textarea::make('account_details')
                    ->required()
                    ->rows(4)
                    ->label('Detail Akun')
                    ->placeholder('Contoh:&#10;Username: john_doe&#10;Password: mypassword123&#10;Email: john@example.com'),
                Forms\Components\TextInput::make('platform_link')
                    ->url()
                    ->maxLength(500)
                    ->label('Link Platform (Opsional)')
                    ->placeholder('https://scratch.mit.edu'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('platform_name')
            ->columns([
                Tables\Columns\TextColumn::make('platform_name')
                    ->label('Platform')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_details')
                    ->label('Detail Akun')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('platform_link')
                    ->label('Link Platform')
                    ->url(fn ($record) => $record->platform_link)
                    ->openUrlInNewTab()
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Akun')
                    ->modalHeading('Tambah Akun Platform Baru')
                    ->modalDescription('Tambahkan akun platform pembelajaran untuk student ini.')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Akun Platform')
                    ->modalDescription('Perbarui informasi akun platform pembelajaran.'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Akun Platform')
                    ->modalDescription('Apakah Anda yakin ingin menghapus akun platform ini?')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Akun Platform Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus akun platform yang dipilih?')
                ]),
            ]);
    }
}
