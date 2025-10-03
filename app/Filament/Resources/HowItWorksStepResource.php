<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HowItWorksStepResource\Pages;
use App\Filament\Resources\HowItWorksStepResource\RelationManagers;
use App\Models\HowItWorksStep;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HowItWorksStepResource extends Resource
{
    protected static ?string $model = HowItWorksStep::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Site Management';
    protected static ?string $navigationLabel = 'How It Works';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title_bn')->label('শিরোনাম (বাংলা)')->required(),
                Forms\Components\Textarea::make('description_bn')->label('বিবরণ (বাংলা)')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('step_number')->label('ধাপ নম্বর')->numeric()->required(),
                Forms\Components\Select::make('color_class')->label('রঙ')
                    ->options([
                        'text-secondary' => 'Secondary',
                        'text-teal' => 'Teal',
                        'text-purple' => 'Purple',
                        'text-primary' => 'Primary',
                    ])->required(),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('step_number')->label('ধাপ')->sortable(),
                Tables\Columns\TextColumn::make('title_bn')->label('শিরোনাম'),
                Tables\Columns\IconColumn::make('is_active')->label('সক্রিয়')->boolean(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
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
            'index' => Pages\ListHowItWorksSteps::route('/'),
            'create' => Pages\CreateHowItWorksStep::route('/create'),
            'edit' => Pages\EditHowItWorksStep::route('/{record}/edit'),
        ];
    }
}
