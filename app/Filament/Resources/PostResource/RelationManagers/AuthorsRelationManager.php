<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorsRelationManager extends RelationManager
{
    protected static string $relationship = 'authors';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               // TextInput::make('name')->required(),
               // TextInput::make('email')->email()->required()->readOnlyOn('edit'),
                //TextInput::make('password')->password()->visibleOn('create'),
                TextInput::make('order')->required()->numeric(),

            ]);
    } 

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('order')->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
               Tables\Actions\AttachAction::make()
               ->form(fn (AttachAction $action): array => [
                $action->getRecordSelect(),
                Forms\Components\TextInput::make('order')->numeric()->required(),
               ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),

                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
               // Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
               // ]),
            ]);
    }
}
