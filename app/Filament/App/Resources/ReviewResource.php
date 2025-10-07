<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ReviewResource\Pages;
use App\Filament\App\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationGroup = 'Interactions';
    protected static ?string $navigationLabel = 'All Reviews';
    protected static ?int $navigationSort = 1;

    /**
     * Set this to false to hide the resource from the navigation menu.
     * এই রিসোর্সটি সাইডবার নেভিগেশনে দেখানো হবে না।
     */
//    protected static bool $shouldRegisterNavigation = false;

    /**
     * This method is the key to scoping all queries for this resource.
     * এটি নিশ্চিত করে যে টেবিল, এডিট, ডিলিট এবং কাউন্ট - সবকিছুই
     * শুধুমাত্র লগইন করা ইউজারের মালিকানাধীন প্রপার্টিগুলোর রিভিউয়ের উপর কাজ করবে।
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('property', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('রিভিউর বিবরণ')
                    ->schema([
                        Forms\Components\Select::make('property_id')->relationship('property', 'title')->disabled(),
                        Forms\Components\Select::make('user_id')->relationship('user', 'name')->disabled(),
                        Forms\Components\TextInput::make('rating')->disabled(),
                        Forms\Components\TextInput::make('title')->disabled(),
                        Forms\Components\Textarea::make('body')->label('বিস্তারিত')->required()->columnSpanFull(),
                        Forms\Components\Select::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])->required(),
                    ])->columns(2),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('ব্যবহারকারী ও মতামত')
                    ->description(fn (Model $record): string => Str::limit($record->body, 100))
                    ->searchable(['body', 'users.name'])
                    ->extraAttributes(fn (Model $record) => !is_null($record->parent_id) ? ['style' => 'padding-left: 2.5rem;'] : [])
                    ->prefix(fn (Model $record) => !is_null($record->parent_id) ? '↳ ' : ''),

                // কলাম ২: কোন প্রপার্টির উপর রিভিউ
                Tables\Columns\TextColumn::make('property.title')
                    ->label('প্রপার্টি')
                    ->searchable()
                    ->url(fn ($record) => PropertyResource::getUrl('edit', ['record' => $record->property])),

                // কলাম ৩: রেটিং
                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' ★' : '-')
                    ->alignCenter(),

                // কলাম ৪: স্ট্যাটাস
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']),
            ])
            ->actions([
                Action::make('reply')
                    ->label('উত্তর দিন')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Forms\Components\Textarea::make('body')->label('আপনার উত্তর')->required(),
                    ])
                    ->action(function (Model $record, array $data): void {
                        $record->replies()->create([
                            'property_id' => $record->property_id,
                            'user_id' => auth()->id(), // উত্তরদাতা হলেন অ্যাডমিন
                            'body' => $data['body'],
                            'rating' => 0,
                            'status' => 'approved',
                        ]);
                        Notification::make()->title('উত্তর সফলভাবে জমা হয়েছে')->success()->send();
                    })
                    ->visible(fn (Model $record): bool => is_null($record->parent_id)),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
