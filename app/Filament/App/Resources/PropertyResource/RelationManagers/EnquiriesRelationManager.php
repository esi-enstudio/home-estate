<?php

namespace App\Filament\App\Resources\PropertyResource\RelationManagers;

use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnquiriesRelationManager extends RelationManager
{
    protected static string $relationship = 'enquiries';

    protected static ?string $title = 'আপনার জিজ্ঞাসাসমূহ (Enquiries)';
    protected static ?string $modelLabel = 'জিজ্ঞাসা';


    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('নাম')
                    // অপঠিত বার্তাগুলোকে বোল্ড করে দেখানো হবে
                    ->weight(fn (Model $record): string => !$record->is_read ? 'bold' : 'normal'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('ফোন')
                    ->copyable() // ফোন নম্বর কপি করার সুবিধা
                    ->copyMessage('ফোন নম্বর কপি করা হয়েছে'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('সময়')
                    ->since(),

                Tables\Columns\TextColumn::make('read_at')
                    ->label('পঠিত')
                    ->dateTime('d M Y, h:i A')
                    ->since()
                    ->placeholder('এখনো পড়া হয়নি'), // যদি read_at null হয়, তাহলে এটি দেখাবে
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
            ])
            ->headerActions([]) // মালিক নতুন জিজ্ঞাসা তৈরি করতে পারবে না
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->infolist([
                        Section::make('ব্যবহারকারীর তথ্য')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('name')->label('নাম'),
                                    TextEntry::make('phone')->label('ফোন নম্বর'),
                                    TextEntry::make('email')->label('ইমেইল')->copyable(),
                                    TextEntry::make('created_at')->label('সময়')->dateTime('d M Y, h:i A'),
                                ]),
                            ]),
                        Section::make('বার্তার বিবরণ')
                            ->schema([
                                TextEntry::make('subject')->label('বিষয়'),
                                TextEntry::make('message')->label('বিস্তারিত বার্তা')
                                    ->formatStateUsing(fn (string $state): string => nl2br(e($state))) // নতুন লাইনগুলোকে সম্মান করবে
                                    ->columnSpanFull(),
                                IconEntry::make('is_read')->label('স্ট্যাটাস')->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->trueColor('success')
                                    ->falseIcon('heroicon-o-eye')
                                    ->falseColor('warning')
                                    ->getStateUsing(fn (Model $record): string => $record->is_read ? 'পঠিত' : 'অপঠিত'),
                            ]),
                    ])
                    ->before(function (Model $record) {
                        // যদি 'read_at' কলামটি আগে থেকেই null না হয় (অর্থাৎ পড়া হয়ে থাকে),
                        // তাহলে অপ্রয়োজনীয় ডাটাবেজ আপডেট করার প্রয়োজন নেই।
                        if (is_null($record->read_at)) {
                            $record->update(['read_at' => now()]);
                        }
                    }),
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
