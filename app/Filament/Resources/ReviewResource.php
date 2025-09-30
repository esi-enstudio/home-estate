<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    // === START: Navigation & Grouping Configuration ===
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'ইন্টার‍্যাকশনস';
    protected static ?string $navigationLabel = 'সকল রিভিউ';
    protected static ?string $modelLabel = 'রিভিউ';
    protected static ?string $pluralModelLabel = 'রিভিউসমূহ';
    protected static ?int $navigationSort = 2;
    // === END: Navigation & Grouping Configuration ===

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('রিভিউর বিবরণ')
                    ->description('ব্যবহারকারীর দেওয়া মূল রিভিউ। এই তথ্যগুলো পরিবর্তন করা উচিত নয়।')
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->relationship('property', 'title')
                            ->label('প্রপার্টি')
                            ->disabled()
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('রিভিউ প্রদানকারী')
                            ->disabled()
                            ->required(),

                        // রেটিং দেখানোর জন্য
                        Forms\Components\Placeholder::make('rating')
                            ->label('রেটিং')
                            ->content(fn ($record) => str_repeat('⭐', $record->rating)),

                        Forms\Components\TextInput::make('title')
                            ->label('শিরোনাম')
                            ->disabled()
                            ->required(),

                        Forms\Components\Textarea::make('body')
                            ->label('বিস্তারিত')
                            ->disabled()
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('মডারেশন ও ম্যানেজমেন্ট')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),

                        Forms\Components\Toggle::make('is_testimonial')
                            ->label('হোমপেজে দেখান')
                            ->helperText('এটি চালু থাকলে রিভিউটি হোমপেজের "Testimonials" সেকশনে দেখানো হবে।'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.title')
                    ->label('প্রপার্টি')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->url(fn (Review $record): string => PropertyResource::getUrl('edit', ['record' => $record->property])),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('রিভিউয়ার')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Review $record): string => UserResource::getUrl('edit', ['record' => $record->user])),

                Tables\Columns\TextColumn::make('rating')
                    ->badge(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_testimonial')
                    ->label('হোমপেজে দেখান'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\TernaryFilter::make('is_testimonial')
                    ->label('হোমপেজে দেখানো'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Model $record) => $record->update(['status' => 'approved']))
                        ->visible(fn (Model $record): bool => $record->status !== 'approved')
                        ->after(fn () => Notification::make()->title('Review Approved')->success()->send()),

                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Model $record) => $record->update(['status' => 'rejected']))
                        ->visible(fn (Model $record): bool => $record->status !== 'rejected')
                        ->after(fn () => Notification::make()->title('Review Rejected')->success()->send()),

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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
