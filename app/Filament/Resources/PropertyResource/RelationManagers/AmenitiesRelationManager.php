<?php

namespace App\Filament\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AmenitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'amenities';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // এই TextInput দিয়ে আপনি পিভট টেবিলের 'details' কলামের ভ্যালু দেবেন
                Forms\Components\TextInput::make('details')
                    ->label('Details (e.g., 1 car parking, Cylinder gas)')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn(Builder $query) => $query->orderBy('amenity_property.created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type')->badge()->formatStateUsing(fn($state): string => Str::title($state)),
                // পিভট টেবিলের 'details' কলামটি দেখানোর জন্য
                Tables\Columns\TextColumn::make('pivot.details')->label('Details'),
//                Tables\Columns\ToggleColumn::make('is_key_feature'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // অ্যাকশন ১: নতুন Amenity তৈরি এবং যুক্ত করার জন্য
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Add New')
                    ->form([
                        // নতুন Amenity তৈরির জন্য প্রয়োজনীয় ফিল্ড
                        TextInput::make('name')
                            ->required()
                            ->unique()
                            ->maxLength(255),

                        TextInput::make('icon_class')
                            ->label('Icon Class (e.g., fas fa-wifi)')
                            ->maxLength(255),

                        Select::make('type')
                            ->options([
                                'facility' => 'Facility',
                                'utility' => 'Utility',
                                'safety' => 'Safety',
                                'environment' => 'Environment',
                            ])
                            ->required(),

                        // পিভট টেবিলের অতিরিক্ত তথ্যের জন্য ফিল্ড
                        TextInput::make('details')
                            ->label('Details (Optional)'),
                    ]),

                // অ্যাকশন ২: বিদ্যমান Amenity যুক্ত করার জন্য
                Tables\Actions\AttachAction::make()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        // এই getRecordSelect() মেথডটি স্বয়ংক্রিয়ভাবে একটি searchable, pre-loadable সিলেক্ট ফিল্ড তৈরি করে
                        $action->getRecordSelect(),
                        // অতিরিক্ত পিভট ডেটার জন্য এখানে ফিল্ড যোগ করুন
                        Forms\Components\TextInput::make('details')->label('Details (Optional)'),
                    ])
                    ->preloadRecordSelect(), // এখন preload এখানে কাজ করবে!
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
