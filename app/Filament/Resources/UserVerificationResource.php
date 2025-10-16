<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserVerificationResource\Pages;
use App\Filament\Resources\UserVerificationResource\RelationManagers;
use App\Models\UserVerification;
use EightyNine\Approvals\Tables\Actions\ApprovalActions;
use EightyNine\Approvals\Tables\Columns\ApprovalStatusColumn;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class UserVerificationResource extends Resource
{
    protected static ?string $model = UserVerification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->visible(fn () => auth()->user()->hasRole('superadmin'))  // শুধু অ্যাডমিন দেখবে
                    ->default(auth()->id()),  // ইউজার প্যানেলে অটো ফিল

            FileUpload::make('birth_certificate')
                ->label('বার্থ সার্টিফিকেট')
                ->disk('public')
                ->directory('verifications/birth')
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->maxSize(5120)  // 5MB
                ->required(fn () => !auth()->user()->hasRole('superadmin'))  // ইউজারের জন্য রিকোয়ার্ড
                ->nullable(),

            FileUpload::make('passport')
                ->label('পাসপোর্ট')
                ->disk('public')
                ->directory('verifications/passport')
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->maxSize(5120)
                ->nullable(),

            FileUpload::make('nid')
                ->label('NID')
                ->disk('public')
                ->directory('verifications/nid')
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->maxSize(5120)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('ইউজার')->sortable(),
                TextColumn::make('birth_certificate')
                    ->label('বার্থ সার্টিফিকেট')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : 'N/A')
                    ->url(fn ($record) => $record->birth_certificate ? Storage::url($record->birth_certificate) : null)
                    ->openUrlInNewTab(),
                TextColumn::make('passport')
                    ->label('পাসপোর্ট')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : 'N/A')
                    ->url(fn ($record) => $record->passport ? Storage::url($record->passport) : null)
                    ->openUrlInNewTab(),
                TextColumn::make('nid')
                    ->label('NID')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : 'N/A')
                    ->url(fn ($record) => $record->nid ? Storage::url($record->nid) : null)
                    ->openUrlInNewTab(),
                ApprovalStatusColumn::make('approvalStatus.status')
                    ->label('স্টেটাস')
                    ->extraAttributes(['class' => 'cursor-pointer'])  // ক্লিক করে হিস্টোরি দেখানোর জন্য
                    ->action(fn ($record) => $record->approvalStatus ? $record->approvalStatus->comments : 'No comments'),  // কমেন্টস দেখান
            ])
            ->filters([
                //
            ])
            ->actions([
                ...ApprovalActions::make(
                    Action::make('ভেরিফাইড সম্পূর্ণ')
                        ->color('success')
                        ->action(function ($record) {
                            $record->user->update(['verified' => true]);
                        }),
                    [EditAction::make(), ViewAction::make()]
                ),
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
            'index' => Pages\ListUserVerifications::route('/'),
            'create' => Pages\CreateUserVerification::route('/create'),
            'edit' => Pages\EditUserVerification::route('/{record}/edit'),
            'view' => Pages\ViewUserVerification::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
