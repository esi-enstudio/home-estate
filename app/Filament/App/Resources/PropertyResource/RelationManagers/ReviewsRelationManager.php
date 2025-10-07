<?php

namespace App\Filament\App\Resources\PropertyResource\RelationManagers;

use App\Models\Review;
use Exception;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';
    protected static ?string $title = 'রিভিউসমূহ এবং উত্তর';
    protected static ?string $modelLabel = 'রিভিউ';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Reviewer'),
                Tables\Columns\TextColumn::make('rating')
                    ->icon('heroicon-s-star')
                    ->color('warning'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('body')->label('Content')->wrap()->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                ColumnGroup::make('প্রতিক্রিয়া (Reactions)', [
                    Tables\Columns\TextColumn::make('likes_count')
                        ->label('Likes')
                        ->badge()
                        // --- মূল পরিবর্তন এখানে ---
                        ->icon('heroicon-s-hand-thumb-up'), // 's-thumb-up' এর পরিবর্তে

                    Tables\Columns\TextColumn::make('dislikes_count')
                        ->label('Dislikes')
                        ->badge()
                        ->color('secondary')
                        // --- মূল পরিবর্তন এখানে ---
                        ->icon('heroicon-s-hand-thumb-down'), // 's-thumb-down' এর পরিবর্তে

                    Tables\Columns\TextColumn::make('favorites_count')
                        ->label('Favorites')
                        ->badge()
                        ->color('danger')
                        // --- মূল পরিবর্তন এখানে ---
                        ->icon('heroicon-s-heart'),
                ])->alignCenter(),
            ])
            ->filters([

            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Textarea::make('body')
                            ->label('Your Reply')
                            ->required(),
                    ])
                    ->action(function (Review $record, array $data) {
                        $record->replies()->create([
                            'property_id' => $record->property_id,
                            'user_id' => $this->getOwnerRecord()->user_id, // প্রপার্টির মালিক
                            'body' => $data['body'],
                            'status' => 'approved', // মালিকের উত্তর স্বয়ংক্রিয়ভাবে অনুমোদিত
                            'rating' => 0, // উত্তর-এর কোনো রেটিং হয় না
                            'title' => 'Reply from owner',
                        ]);

                        Notification::make()
                            ->title('Reply posted successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
