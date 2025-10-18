<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IdentityVerificationResource\Pages;
use App\Filament\Resources\IdentityVerificationResource\RelationManagers;
use App\Models\IdentityVerification;
use Filament\Forms;
use Filament\Forms\Form;
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

class IdentityVerificationResource extends Resource
{
    protected static ?string $model = IdentityVerification::class;
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'ইউজার ম্যানেজমেন্ট';

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
                        TextEntry::make('user.name')->label('User Name'),
                        TextEntry::make('user.email')->label('User Email'),
                    ])->columns(2),
                Section::make('Document Information')
                    ->schema([
                        TextEntry::make('id_type')->label('ID Type'),
                        TextEntry::make('id_number')->label('ID Number'),
                        ImageEntry::make('front_image')
                            ->label('Front Side')
                            ->disk('public')
                            ->width(300)
                            ->height(200),
                        ImageEntry::make('back_image')
                            ->label('Back Side')
                            ->disk('public')
                            ->width(300)
                            ->height(200)
                            // ঐচ্ছিক: যদি পেছনের ছবি না থাকে, তাহলে এটি দেখাবে না
                            ->visible(fn ($record) => $record->back_image),
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
     * @throws \Exception
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
                        $record->update([
                            'status' => 'rejected',
                            'rejected_at' => now(),
                            'rejection_reason' => $data['rejection_reason'],
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
//            'edit' => Pages\EditIdentityVerification::route('/{record}/edit'),
        ];
    }
}
