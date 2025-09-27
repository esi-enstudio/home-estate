<?php

namespace App\Filament\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnquiriesRelationManager extends RelationManager
{
    protected static string $relationship = 'enquiries';

    public function form(Form $form): Form
    {
        // এই ফর্মটি "View" এবং "Edit" অ্যাকশনের জন্য ব্যবহৃত হবে
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->disabled(),
                Forms\Components\TextInput::make('phone')
                    ->disabled(),
                Forms\Components\Textarea::make('message')
                    ->columnSpanFull()
                    ->disabled(),
                Forms\Components\Toggle::make('is_read')
                    ->label('Mark as Read'),
            ]);
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone'),

                // is_read স্ট্যাটাসটি সরাসরি টেবিল থেকেই পরিবর্তন করা যাবে
                Tables\Columns\ToggleColumn::make('is_read')
                    ->label('Read'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
            ])
            ->headerActions([
                // আমরা অ্যাডমিন প্যানেল থেকে নতুন জিজ্ঞাসা তৈরি করতে দেব না, তাই এটি খালি থাকবে
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // একাধিক Enquiry-কে একসাথে "Read" হিসেবে মার্ক করার জন্য
                    Tables\Actions\BulkAction::make('markAsRead')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['is_read' => true]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
