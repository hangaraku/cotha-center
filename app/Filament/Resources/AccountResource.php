<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Akun Platform';
    protected static ?string $modelLabel = 'Akun Platform';
    protected static ?string $pluralModelLabel = 'Akun Platform';
    protected static ?string $navigationGroup = 'Account Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Student')
                    ->options(function () {
                        return User::whereBelongsTo(Auth::user()->center)
                            ->where(function ($query) {
                                $query->whereHas('roles', function ($q) {
                                    $q->where('name', 'Student');
                                })
                                ->orWhereDoesntHave('roles');
                            })
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->label('Pilih Student'),
                Forms\Components\TextInput::make('platform_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Platform')
                    ->placeholder('Contoh: Scratch, Construct 3, Unity'),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Student')
                    ->options(function () {
                        return User::whereBelongsTo(Auth::user()->center)
                            ->where(function ($query) {
                                $query->whereHas('roles', function ($q) {
                                    $q->where('name', 'Student');
                                })
                                ->orWhereDoesntHave('roles');
                            })
                            ->pluck('name', 'id');
                    })
                    ->searchable(),
                Tables\Filters\Filter::make('platform_name')
                    ->form([
                        Forms\Components\TextInput::make('platform_name')
                            ->label('Nama Platform')
                            ->placeholder('Cari berdasarkan nama platform...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['platform_name'],
                                fn (Builder $query, $platformName): Builder => $query->where('platform_name', 'like', "%{$platformName}%"),
                            );
                    })
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user', function ($query) {
                $query->whereBelongsTo(Auth::user()->center);
            });
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        
        // Check if user has admin roles (Super Admin, Teacher, Supervisor)
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
    }

    /**
     * Check if user is admin (Super Admin, Teacher, or Supervisor)
     */
    public static function isUserAdmin(User $user): bool
    {
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
    }
}
