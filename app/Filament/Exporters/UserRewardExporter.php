<?php

namespace App\Filament\Exporters;

use App\Models\UserReward;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserRewardExporter extends Exporter
{
    protected static ?string $model = UserReward::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Order ID'),
            ExportColumn::make('user.name')
                ->label('Student Name'),
            ExportColumn::make('user.students.school')
                ->label('School'),
            ExportColumn::make('reward.name')
                ->label('Reward'),
            ExportColumn::make('reward.price')
                ->label('Price'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => match ($state) {
                    0 => 'Pending',
                    1 => 'Claimed',
                    2 => 'Cancelled',
                    default => 'Unknown',
                }),
            ExportColumn::make('created_at')
                ->label('Order Date')
                ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s')),
            ExportColumn::make('updated_at')
                ->label('Updated At')
                ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Your student orders CSV export is ready.';
    }
}
