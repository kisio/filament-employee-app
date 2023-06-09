<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\Country;
use App\Models\Employees;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class EmployeesResource extends Resource
{
    protected static ?string $model = Employees::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        Card::make()
            ->schema([
                Select::make('country_id')->relationship('country', 'name')->required()
                ->label('country')->options(Country::all()
                ->pluck('name','id')->toArray())
                ->reactive()
                ->afterStateUpdated(fn(callable $set)=>$set('state_id',null)),
                Select::make('state_id')
                ->label('State')
                ->options(function (callable $get){
                    $country=Country::find($get('country_id'));
                    if(!$country){
                        return State::all()->pluck('name','id');
                    }
                    return $country->states->pluck('name','id');
                })->reactive()
                ->afterStateUpdated(fn(callable $set)=>$set('city_id',null)),
                //
                Select::make('city_id')
                ->label('City')
                ->options(function (callable $get){
                    $state=State::find($get('state_id'));
                    if(!$state){
                        return State::all()->pluck('name','id');
                    }
                    return $state->cities->pluck('name','id');
                })->reactive()
                ->afterStateUpdated(fn(callable $set)=>$set('city_id',null)),
                Select::make('department_id')->relationship('department', 'name')->required(),

                TextInput::make('first_name')->required(),
                TextInput::make('last_name')->required(),
                TextInput::make('address')->required(),
                TextInput::make('zip_code')->required(),
                DatePicker::make('birth_date')->required(),
                DatePicker::make('date_hired')->required(),
            ])
    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable(),
                TextColumn::make('department.name')->sortable(),
                TextColumn::make('date_hired')->date(),
                TextColumn::make('created_at')->dateTime()
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department',name)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployees::route('/create'),
            'edit' => Pages\EditEmployees::route('/{record}/edit'),
        ];
    }
}
