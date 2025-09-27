<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\PropertyResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WishlistRelationManager extends RelationManager
{
    protected static string $relationship = 'wishlist';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
                    ->collection('featured_image')
                    ->conversion('thumbnail'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner'),
                Tables\Columns\TextColumn::make('rent_price')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.created_at') // পিভট টেবিল থেকে তারিখ
                ->label('Added to Wishlist')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect() // এটি সার্চকে আরও দ্রুত করে
                    ->label('Add Property to Wishlist'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(fn ($record) => PropertyResource::getUrl('view', ['record' => $record])),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
