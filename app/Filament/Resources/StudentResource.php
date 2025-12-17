<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Students';
    protected static ?string $slug = 'students';


    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Account Management';

   protected static bool $shouldRegisterNavigation = true;
public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('center_id')->relationship('center','name')
            ->default(Auth::user()->center->id)->label("Center"),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('profile_picture')
                ->label('Profile Picture')
                ->image()
                ->disk('public')
                ->visibility('public')
                ->maxSize(6144)
                ->imageEditor()
                ->imageEditorAspectRatios([
                    '1:1',
                ])
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->imageResizeTargetWidth('500')
                ->imageResizeTargetHeight('500')
                ->helperText('Upload a profile picture (max 6MB). Recommended size: 500x500px')
                ->columnSpanFull(),
            Forms\Components\DateTimePicker::make('email_verified_at'),
            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create')
                ->maxLength(255),
                Forms\Components\Select::make('center_id')->relationship('center','name')
                ->default(Auth::user()->center)->disabled()->label("Center"),
            Forms\Components\Fieldset::make('Student Detail')
                ->relationship('students')
                ->schema([
                    Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('school')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\DatePicker::make('birthdate')
                    ->required(),
                    Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\RichEditor::make('note')->columnSpanFull()
,
                    Forms\Components\Checkbox::make('status')->label("Mark Student as Active?")
                    
                
                ])        
            ]);
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereBelongsTo(Auth::user()->center)
            ->where(function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'Student');
                })
                ->orWhereDoesntHave('roles');
            });
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->label('Photo')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(url('/images/logo.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('students.status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        $normalized = strtolower((string) $state);
                        return in_array($normalized, ['1', 'active']) ? 'Active' : 'Inactive';
                    })
                    ->color(function ($state) {
                        if($state == 'active' || $state == 'Active' || $state == 1 || $state == '1'){
                            return 'success';
                        }else{
                            return 'danger';
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('students.birthdate')
                    ->label('Birthdate')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('students.school')
                    ->label('School')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('students.phone')
                    ->label('Phone'),
            ])
            ->filters([
                Filter::make('status')
                    ->form([
                        Select::make('value')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        if (empty($data['value'])) {
                            return $query;
                        }
                        if ($data['value'] === 'active') {
                            return $query->whereHas('students', function ($q) {
                                $q->whereIn('status', [1, '1', 'active', 'Active']);
                            });
                        }
                        if ($data['value'] === 'inactive') {
                            return $query->whereHas('students', function ($q) {
                                $q->whereNotIn('status', [1, '1', 'active', 'Active']);
                            });
                        }
                        return $query;
                    })
                    ->label('Status'),
                Filter::make('school')
                    ->form([
                        Select::make('value')
                            ->label('School')
                            ->options(function () {
                                return Student::whereHas('user', function ($query) {
                                    $query->whereBelongsTo(Auth::user()->center);
                                })
                                ->whereNotNull('school')
                                ->distinct()
                                ->pluck('school', 'school')
                                ->toArray();
                            })
                            ->searchable(),
                    ])
                    ->query(function ($query, array $data) {
                        if (empty($data['value'])) {
                            return $query;
                        }
                        return $query->whereHas('students', function ($q) use ($data) {
                            $q->where('school', $data['value']);
                        });
                    })
                    ->label('School'),
            ])
            ->defaultSort('name')
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
            RelationManagers\AccountsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }    
}
