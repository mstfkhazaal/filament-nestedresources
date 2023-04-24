<?php


namespace Mstfkhazaal\FilamentNestedresources\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;

class CreateNestedRecords extends CreateRecord{
    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        if ($resource::hasPage('view') && $resource::canView($this->record)) {
            return $resource::getUrl('view',[...$this->urlParameters, 'record' => $record]);
        }

        if ($resource::hasPage('edit') && $resource::canEdit($this->record)) {
            return $resource::getUrl( 'edit',
                [...$this->urlParameters, 'record' => $record]);
        }

        return $resource::getUrl('index');
    }


}
