<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployees extends CreateRecord
{
    protected static string $resource = EmployeesResource::class;
}
