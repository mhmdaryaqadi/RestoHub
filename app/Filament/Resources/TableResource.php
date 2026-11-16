<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TableResource\Pages;
use App\Models\Table as TableModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'Meja Restoran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('table_number')
                    ->label('Nomor Meja')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),
                Forms\Components\TextInput::make('floor')
                    ->label('Lantai')
                    ->required()
                    ->maxLength(50)
                    ->default('Lt 1'),
                Forms\Components\TextInput::make('capacity')
                    ->label('Kapasitas Kursi')
                    ->required()
                    ->numeric()
                    ->default(4),
                Forms\Components\Select::make('status')
                    ->label('Status Meja')
                    ->options([
                        'available' => 'Tersedia',
                        'occupied' => 'Terisi',
                    ])
                    ->default('available')
                    ->required(),
                Forms\Components\TextInput::make('qr_token')
                    ->label('Token QR Meja')
                    ->default(fn () => 'qr-' . bin2hex(random_bytes(6)))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(64),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('table_number')
                    ->label('No. Meja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('floor')
                    ->label('Lantai')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                Tables\Columns\TextColumn::make('qr_token')
                    ->label('QR Token')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}
