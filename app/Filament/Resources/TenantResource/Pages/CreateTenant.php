<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantTypeResource::class;
}
