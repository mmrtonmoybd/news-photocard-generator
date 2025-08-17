<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationGroup = 'Packages';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('details')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_credit')
                    ->default(false)
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('credit')
                    ->numeric()
                    ->default(0)
                    ->dehydrated(fn (Get $get) => $get('is_credit'))
                    ->visible(fn (Get $get) => $get('is_credit')),
                Forms\Components\TextInput::make('days')
                    ->numeric()
                    ->default(0)
                    ->dehydrated(fn (Get $get) => ! $get('is_credit'))
                    ->visible(fn (Get $get) => ! $get('is_credit')),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('TK.'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_credit')
                    ->boolean(),
                Tables\Columns\TextColumn::make('credit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManagePackages::route('/'),
        ];
    }
}
