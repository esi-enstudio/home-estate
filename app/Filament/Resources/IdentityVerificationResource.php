<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IdentityVerificationResource\Pages;
use App\Filament\Resources\IdentityVerificationResource\RelationManagers;
use App\Models\IdentityVerification;
use Carbon\Carbon;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class IdentityVerificationResource extends Resource
{
    protected static ?string $model = IdentityVerification::class;
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    // View page Infolist
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Information')
                    ->schema([
                        // ইউজার অ্যাভাটার এর জন্য
                        ImageEntry::make('user.avatar_url')
                            ->label('User Avatar')
                            ->disk('public')
                            ->extraAttributes([
                                'class' => 'w-full h-auto object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity',
                                'loading' => 'lazy',
                            ])
                            ->visible(fn ($record) => $record->user->avatar_url)
                            // --- ক্লিক করলে মডাল দেখানোর কোড ---
                            ->action(
                                Action::make('view_avatar')
                                    ->label('') // ছবিতে কোনো লেবেল দেখাবে না
                                    ->modalContent(fn (IdentityVerification $record): HtmlString => new HtmlString(
                                        '<img src="' . Storage::disk('public')->url($record->user->avatar_url) . '" class="w-full h-auto rounded-lg">'
                                    ))
                                    ->modalHeading('User Avatar')
                                    ->modalSubmitAction(false) // Submit বাটন লুকিয়ে ফেলবে
                                    ->modalCancelAction(false) // Cancel বাটন লুকিয়ে ফেলবে
                                    ->modalWidth('3xl') // মডালের আকার
                            ),

                        TextEntry::make('user.name')->label('User Name'),
                        TextEntry::make('user.email')->label('User Email'),
                    ])->columns(2),

                Section::make('Document Information')
                    ->schema([
                        TextEntry::make('id_type')->label('ID Type'),
                        TextEntry::make('id_number')->label('ID Number'),

                        // আইডি কার্ডের সামনের ছবি
                        ImageEntry::make('front_image')
                            ->label('Front Side')
                            ->disk('public')
                            ->extraAttributes([
                                'class' => 'w-full h-auto object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity',
                                'loading' => 'lazy',
                            ])
                            // --- ক্লিক করলে মডাল দেখানোর কোড ---
                            ->action(
                                Action::make('view_front_image')
                                    ->label('')
                                    ->modalContent(fn (IdentityVerification $record): HtmlString => new HtmlString(
                                        '<img src="' . Storage::disk('public')->url($record->front_image) . '" class="w-full h-auto rounded-lg">'
                                    ))
                                    ->modalHeading('Front Side')
                                    ->modalSubmitAction(false)
                                    ->modalCancelAction(false)
                                    ->modalWidth('4xl')
                            ),

                        // আইডি কার্ডের পেছনের ছবি
                        ImageEntry::make('back_image')
                            ->label('Back Side')
                            ->disk('public')
                            ->extraAttributes([
                                'class' => 'w-full h-auto object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity',
                                'loading' => 'lazy',
                            ])
                            ->visible(fn ($record) => $record->back_image)
                            // --- ক্লিক করলে মডাল দেখানোর কোড ---
                            ->action(
                                Action::make('view_back_image')
                                    ->label('')
                                    ->modalContent(fn (IdentityVerification $record): HtmlString => new HtmlString(
                                        '<img src="' . Storage::disk('public')->url($record->back_image) . '" class="w-full h-auto rounded-lg">'
                                    ))
                                    ->modalHeading('Back Side')
                                    ->modalSubmitAction(false)
                                    ->modalCancelAction(false)
                                    ->modalWidth('4xl')
                            ),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            }),
                        TextEntry::make('rejection_reason')->visible(fn ($record) => $record->status === 'rejected'),
                    ])->columns(2),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_type')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state->toFormattedDayDateString())
                    ->sortable(),
                Tables\Columns\TextColumn::make('rejected_at')
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state->toFormattedDayDateString())
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(fn (IdentityVerification $record) => $record->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'rejection_reason' => null,
                    ]))
                    ->visible(fn (IdentityVerification $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Reason for Rejection')
                            ->required(),
                    ])
                    ->action(function (IdentityVerification $record, array $data) {
                        // --- ডকুমেন্ট ডিলিট করার কোড শুরু ---
                        if ($record->front_image) {
                            Storage::disk('public')->delete($record->front_image);
                        }
                        if ($record->back_image) {
                            Storage::disk('public')->delete($record->back_image);
                        }
                        // --- ডকুমেন্ট ডিলিট করার কোড শেষ ---

                        $record->update([
                            'status' => 'rejected',
                            'rejected_at' => now(),
                            'rejection_reason' => $data['rejection_reason'],
                            'front_image' => 'deleted', // ঐচ্ছিক
                            'back_image' => null,     // ঐচ্ছিক
                        ]);
                    })
                    ->visible(fn (IdentityVerification $record) => $record->status === 'pending'),
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
            'index' => Pages\ListIdentityVerifications::route('/'),
            'create' => Pages\CreateIdentityVerification::route('/create'),
            'view' => Pages\ViewIdentityVerification::route('/{record}')
//            'edit' => Pages\EditIdentityVerification::route('/{record}/edit'),
        ];
    }
}
