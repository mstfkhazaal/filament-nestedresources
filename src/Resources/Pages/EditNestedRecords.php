<?php


namespace Mstfkhazaal\FilamentNestedresources\Resources\Pages;

use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ForceDeleteAction;
use Filament\Pages\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;

class EditNestedRecords extends EditRecord{
    protected function configureForceDeleteAction(ForceDeleteAction $action): void
    {
        $resource = static::getResource();
        $action
            ->authorize(fn (Model $record): bool => $resource::canForceDelete($record))
            ->record($this->getRecord())
            ->recordTitle($this->getRecordTitle())
            ->successRedirectUrl($resource::getUrl('index',$this->urlParameters));
    }
    protected function configureDeleteAction(DeleteAction $action): void
    {
        $resource = static::getResource();
        dd($this->getRecord());


        $action
            ->authorize(fn (Model $record): bool => $resource::canDelete($record))
            ->record($this->getRecord())
            ->recordTitle($this->getRecordTitle())
            ->successRedirectUrl($resource::getUrl('index',$this->urlParameters));
    }


    protected function configureViewAction(ViewAction $action): void
    {
        $resource = static::getResource();

        $action
            ->authorize(fn (Model $record): bool => $resource::canView($record))
            ->record($this->getRecord())
            ->recordTitle($this->getRecordTitle());

        if ($resource::hasPage('view')) {
            $action->url(fn (): string => static::getResource()::getUrl('view', [...$this->urlParameters, 'record' => $record]));

            return;
        }

        $action->form($this->getFormSchema());
    }


}
