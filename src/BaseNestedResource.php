<?php

namespace Mstfkhazaal\FilamentNestedresources;

use Closure;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

abstract class BaseNestedResource extends Resource
{

    protected static ?string $columnBreadcrumb ='name';

    public static function getColumnBreadcrumb(): ?string
    {
        return static::$columnBreadcrumb;
    }
}
