<?php

namespace App\Filament\Resources\PropertyResource\RelationManagers;

use App\Models\Enquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnquiriesRelationManager extends RelationManager
{
    protected static string $relationship = 'enquiries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'read' => 'info',
                        'contacted' => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'read' => 'Read',
                        'contacted' => 'Contacted',
                    ]),
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('markAsContacted')
                    ->label('Mark as Contacted')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Enquiry $record) {
                        $record->update(['status' => 'contacted', 'is_read' => true, 'read_at' => now()]);
                        $this->dispatch('refresh'); // টেবিল রিফ্রেশ করার জন্য
                    })
                    ->visible(fn (Enquiry $record): bool => $record->status !== 'contacted'),

                // মূল EnquiryResource এ যাওয়ার জন্য বাটন
                Tables\Actions\Action::make('view_details')
                    ->label('View Details')
                    ->url(fn (Enquiry $record): string => \App\Filament\Resources\EnquiryResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-arrow-top-right-on-square'),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
