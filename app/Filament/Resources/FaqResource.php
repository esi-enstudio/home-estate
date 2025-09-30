<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Filament\Resources\FaqResource\RelationManagers;
use App\Models\Faq;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    // === START: Navigation & Grouping Configuration ===
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'ওয়েবসাইট ম্যানেজমেন্ট';
    protected static ?string $navigationLabel = 'সচরাচর জিজ্ঞাসিত প্রশ্ন';
    protected static ?string $modelLabel = 'প্রশ্ন';
    protected static ?string $pluralModelLabel = 'প্রশ্নসমূহ';
    protected static ?int $navigationSort = 4;
    // === END: Navigation & Grouping Configuration ===

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('প্রশ্ন ও উত্তর')
                    ->schema([
                        Forms\Components\Textarea::make('question')
                            ->label('প্রশ্ন')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('answer')
                            ->label('উত্তর')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('স্ট্যাটাস ও ক্রম')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('সাজানোর ক্রম (Order)')
                            ->numeric()
                            ->default(0)
                            ->helperText('ছোট সংখ্যাগুলো আগে দেখানো হবে।'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('সক্রিয়')
                            ->default(true)
                            ->helperText('এটি নিষ্ক্রিয় করলে প্রশ্নটি ওয়েবসাইটে দেখানো হবে না।'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('হোমপেজে দেখান')
                            ->default(true)
                            ->helperText('এটি চালু থাকলে প্রশ্নটি হোমপেজের FAQ সেকশনে প্রদর্শিত হবে।'),
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('প্রশ্ন')
                    ->searchable()
                    ->limit(60)
                    ->wrap(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('সক্রিয়'),

                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('হোমপেজে দেখান'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ক্রম')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('সক্রিয় স্ট্যাটাস'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('হোমপেজের স্ট্যাটাস'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
