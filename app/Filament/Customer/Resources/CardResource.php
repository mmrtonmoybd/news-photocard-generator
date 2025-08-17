<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\CardResource\Pages;
use App\Models\Card;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Image cards';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('frame_id')
                    ->label('Frame')
                    ->searchable()
                    ->preload()
                    ->relationship('frame', 'name', fn (Builder $query) => $query->where(function ($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
        })
                    )
                    ->required(),

                Forms\Components\Textarea::make('title')
                    ->required(),

                Forms\Components\ColorPicker::make('title_color')
                    ->label('Title color')
                    ->default('#ffffff')
                    ->required(),

                Forms\Components\Toggle::make('is_date')
                    ->label('Show Date')
                    ->default(false)
                    ->live()
                    ->required(),

                Grid::make(3)->schema([
                    Forms\Components\Toggle::make('is_bangla_date')
                        ->label('Bangla Date')
                        ->default(false)
                        ->dehydrated(fn (Get $get) => $get('is_date')),

                    Forms\Components\ColorPicker::make('date_color')
                        ->default('#000000')
                        ->dehydrated(fn (Get $get) => $get('is_date')),

                    Forms\Components\DatePicker::make('date')
                        ->required(fn (Get $get) => $get('is_date'))
                        ->dehydrated(fn (Get $get) => $get('is_date')),
            ])->visible(fn (Get $get) => $get('is_date')),

                Forms\Components\Toggle::make('is_logo')
                    ->label('Show Logo')
                    ->default(false)
                    ->live()
                    ->required(),

                Grid::make(1)->schema([

                    Forms\Components\Select::make('logo_id')
                        ->relationship('logo', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\FileUpload::make('logo')
                                ->required()
                                ->imageEditor()
                                ->image(),
                        ])
                        ->required(fn (Get $get) => $get('is_logo'))
                        ->dehydrated(fn (Get $get) => $get('is_logo')),
            ])->visible(fn (Get $get) => $get('is_logo')),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required()
                    ->dehydrated(true),

                Forms\Components\ColorPicker::make('image_border')
                    ->default('#000000'),

                Forms\Components\Select::make('footer_text_id')
                    ->relationship('footerText', 'text')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('text')
                            ->required(),
                    ])->live(),

                Forms\Components\ColorPicker::make('footer_text_color')
                    ->default('#030303ff')
                    ->dehydrated(fn (Get $get) => filled($get('footer_text_id')))
                    ->visible(fn (Get $get) => filled($get('footer_text_id'))),

                Forms\Components\Select::make('format')
                    ->options([
                        'png' => 'PNG',
                        'jpg' => 'JPG',
                        'jpeg' => 'JPEG',
                        'webp' => 'WEBP',
                    ])
                    ->default('png')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('frame.image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('format'),
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
                Tables\Actions\ButtonAction::make('download')->visible(function (Card $record) {
                    if (! empty(auth()->user()->package_id)) {

                        $package = Package::find(auth()->user()->package_id);

                        if ($package->is_credit === 0) {
                            $isValid = Carbon::parse($package->package_expiry_date)->startOfDay()
                                       >= Carbon::parse($record->created_at)->startOfDay();

                            return $isValid;
                        } else {
                            if (! empty($record->generated_image)) {
                                return true;
                            } else {
                                return (auth()->user()->credit > 0) ? true : false;
                            }
                        }
                    } else {
                        return false;
                    }
                })->url(fn (Card $record) => route('filament.customer.resources.cards.download', $record)),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'view' => Pages\ViewCard::route('/{record}'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
            'download' => Pages\DownloadImage::route('/{record}/download'),
        ];
    }
}
