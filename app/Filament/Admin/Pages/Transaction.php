<?php

namespace App\Filament\Admin\Pages;

use App\Models\Payment;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;

class Transaction extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Packages';

    public function table(Table $table)
    {
        return $table->query(fn (Payment $payment) => $payment->where('status', 'success'))->columns([
            Tables\Columns\TextColumn::make('own_transaction_id')->label('Transaction ID')->searchable(),
            Tables\Columns\TextColumn::make('package.name')->label('Package')->searchable(),
            Tables\Columns\TextColumn::make('user.name')->searchable(),
            Tables\Columns\TextColumn::make('amount')->searchable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Payment at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ]);
    }

    protected static string $view = 'filament.admin.pages.transaction';
}
