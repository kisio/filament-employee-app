<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployees extends EditRecord
{
    protected static string $resource = EmployeesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
