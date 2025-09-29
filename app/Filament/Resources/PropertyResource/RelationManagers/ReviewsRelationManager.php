<?php

namespace App\Filament\Resources\PropertyResource\RelationManagers;

use App\Models\Review;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Reviews and Replies';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),

                Forms\Components\Toggle::make('is_testimonial')
                    ->label('Show this review on the homepage testimonials section.'),
            ]);
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
                Tables\Columns\TextColumn::make('title')
                    ->limit(20),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\ToggleColumn::make('is_testimonial')
                    ->label('হোমপেজে দেখান'),
                Tables\Columns\TextColumn::make('replies_count')
                    ->label('Replies')
                    ->counts('replies'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_testimonial')
                    ->label('হোমপেজে দেখানো'),
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
