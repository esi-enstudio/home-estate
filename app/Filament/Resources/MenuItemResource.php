<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationGroup = 'Site Management';
    protected static ?string $navigationLabel = 'Navigation Menu';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('লিংকের লেখা (Label)')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('url')
                    ->label('লিংকের URL বা #')
                    ->required()
                    ->helperText('সাব-মেন্যু থাকলে URL হিসেবে `#` ব্যবহার করুন।'),

                Forms\Components\Select::make('parent_id')
                    ->label('প্যারেন্ট মেন্যু')
                    // === START: মূল পরিবর্তন এখানে ===
                    ->relationship('parent', 'label', function (Builder $query, ?MenuItem $record) {
                        // যদি এটি Edit মোডে থাকে, তাহলে নিজেকে প্যারেন্ট হিসেবে দেখানো যাবে না
                        if ($record) {
                            $query->where('id', '!=', $record->id);
                        }
                        return $query;
                    })
                    // === END ===
                    ->searchable()
                    ->preload()
                    ->placeholder('এটি একটি টপ-লেভেল মেন্যু'),

                Forms\Components\TextInput::make('sort_order')
                    ->label('সাজানোর ক্রম')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('সক্রিয়')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('মেন্যু আইটেম'),
            ])
            ->paginated(false) // ট্রি-ভিউয়ের জন্য পেজিনেশন বন্ধ করা ভালো
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
