<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use App\Models\Property;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    /**
     * টেবিলের উপরে ট্যাবগুলো ডিফাইন করার জন্য নতুন এবং সঠিক পদ্ধতি।
     */
    public function getTabs(): array
    {
        return [
            'all' => ListRecords\Tab::make('All Properties')
                ->badge(static::getResource()::getModel()::count()),

            'active' => ListRecords\Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active'))
                ->badge(static::getResource()::getModel()::where('status', 'active')->count())
                ->badgeColor('success'),

            'pending' => ListRecords\Tab::make('Pending Approval')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(static::getResource()::getModel()::where('status', 'pending')->count())
                ->badgeColor('warning'),

            'rented_or_sold' => ListRecords\Tab::make('Rented / Sold')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['rented', 'sold_out']))
                ->badge(static::getResource()::getModel()::whereIn('status', ['rented', 'sold_out'])->count())
                ->badgeColor('info'),

            'inactive' => ListRecords\Tab::make('Inactive')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'inactive'))
                ->badge(static::getResource()::getModel()::where('status', 'inactive')->count())
                ->badgeColor('danger'),
        ];
    }
}
