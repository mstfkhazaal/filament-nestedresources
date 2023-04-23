<?php

namespace Mstfkhazaal\FilamentNestedresources\Columns;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;
use Mstfkhazaal\FilamentNestedresources\NestedResource;

class ChildResourceLink extends TextColumn
{
    /**
     * @var NestedResource
     */
    private string $resourceClass;

    /**
     * @param  NestedResource  $name
     */
    public static function make(string $name): static
    {
        $item = parent::make($name);
        $item->forResource($name);
        $item->label($item->getChildLabelPlural());

        return $item;
    }

    public function getChildLabelPlural(): string
    {
        return Str::title($this->resourceClass::getPluralModelLabel());
    }

    public function getChildLabelSingular(): string
    {
        return Str::title($this->resourceClass::getModelLabel());
    }

    public function forResource(string $resourceClass): static
    {
        $this->resourceClass = $resourceClass;

        return $this;
    }

    public function getState(): string
    {
        $count = $this->getCount();

        return $count.' '.($count === 1 ? $this->getChildLabelSingular() : $this->getChildLabelPlural());
    }

    public function getUrl(): ?string
    {
        $baseParams = [];
        if (property_exists($this->table->getLivewire(), 'urlParameters')) {
            $baseParams = $this->table->getLivewire()->urlParameters;
        }

        $param = Str::camel(Str::singular($this->resourceClass::getParent()::getSlug())); // 👈 Here
        // Which is the same used in "NestedResource::getParentTree()" in the "urlPlaceholder" parameter

        return $this->resourceClass::getUrl(
            'index',
            [...$baseParams, $param => $this->record->getKey()] // 👈 Here
        );
    }

    private function getCount(): int
    {
        return $this->resourceClass::getEloquentQuery($this->record->getKey())->count();
    }
}
