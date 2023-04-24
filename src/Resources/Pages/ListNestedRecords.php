<?php


namespace Mstfkhazaal\FilamentNestedresources\Resources\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;

class ListNestedRecords extends ListRecords{

    protected function configureEditAction(Tables\Actions\EditAction $action): void
    {
        $resource = static::getResource();

        $action
            ->authorize(fn (Model $record): bool => $resource::canEdit($record))
            ->form(fn (): array => $this->getEditFormSchema());

        if ($resource::hasPage('edit')) {
            $action->url(fn (Model $record): string => $resource::getUrl(
                'edit',
                [...$this->urlParameters, 'record' => $record]
            ));
        }
    }

    protected function configureViewAction(Tables\Actions\ViewAction $action): void
    {
        $resource = static::getResource();

        $action
            ->authorize(fn (Model $record): bool => $resource::canView($record))
            ->form(fn (): array => $this->getViewFormSchema());
        if ($resource::hasPage('view')) {
            $action->url(fn (Model $record): string => $resource::getUrl(
                'view',
                [...$this->urlParameters, 'record' => $record]
            ));
        }

    }
}
