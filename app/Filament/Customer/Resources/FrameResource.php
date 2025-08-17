<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\FrameResource\Pages;
use App\Models\Frame;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class FrameResource extends Resource
{
    protected static ?string $model = Frame::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Image cards';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->imageEditor()
                    ->rules([
                        Rule::dimensions()
                            ->minWidth(1280)
                            ->minHeight(1280)
                            ->maxWidth(1280)
                            ->maxHeight(1280),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->required()->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
                Tables\Actions\EditAction::make()->disabled(fn(Frame $frame) => empty($frame->user_id)),
                Tables\Actions\DeleteAction::make()->disabled(fn(Frame $frame) => empty($frame->user_id)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFrames::route('/'),
        ];
    }

    
    public static function getEloquentQuery() : Builder
    {
        return parent::getEloquentQuery()->where(function ($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
        });
    }

    
}
