<?php

namespace Mstfkhazaal\FilamentNestedresources\ResourcePages;

use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Form;
use Filament\Tables\Actions\DeleteAction as FilamentDeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mstfkhazaal\FilamentNestedresources\NestedResource;
use Filament\Resources\Pages\Concerns\HasActiveFormLocaleSwitcher;

/**
 * @mixin Filament\Resources\Pages\EditRecord
 */
trait CreateNestedTranslatable
{

    use HasActiveFormLocaleSwitcher;

    public function mount(): void
    {
        static::authorizeResourceAccess();

        abort_unless(static::getResource()::canCreate(), 403);

        $this->setActiveFormLocale();

        $this->fillForm();
    }

    protected function setActiveFormLocale(): void
    {
        $this->activeLocale = $this->activeFormLocale = static::getResource()::getDefaultTranslatableLocale();
    }
    protected function getActions(): array
    {
        return array_merge(
            [$this->getActiveFormLocaleSelectAction()],
            parent::getActions() ?? [],
        );
    }
    ///
    public array $urlParameters;

    /**
     * @return class-string<NestedResource>|NestedResource
     */
    abstract public static function getResource(): string;

    public function bootNestedPage()
    {
        if (empty($this->urlParameters)) {
            $this->urlParameters = $this->getUrlParametersForState();
        }
    }

    public function mountNestedPage()
    {
        if (empty($this->urlParameters)) {
            $this->urlParameters = $this->getUrlParametersForState();
        }
    }

    protected function getUrlParametersForState(): array
    {
        $parameters = Route::current()->parameters;

        foreach ($parameters as $key => $value) {
            if ($value instanceof Model) {
                $parameters[$key] = $value->getKey();
            }
        }

        return $parameters;
    }

    protected function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        // Build the nested breadcrumbs.
        $nestedCrumbs = [];
        foreach ($resource::getParentTree(static::getResource()::getParent(), $this->urlParameters) as $nested) {
            $nestedCrumbs[$nested->getListUrl()] = $nested->resource::getBreadcrumb();
            $model= $nested->resource::getModel();
            $str= $model::find($nested->id);
            try{
                $column = $nested->resource::getColumnBreadcrumb();
                if($str->$column==null){
                    $nestedCrumbs[$nested->getEditUrl()] = $nested->getBreadcrumbTitle();
                }else{
                    $nestedCrumbs[$nested->getEditUrl()] = $str->$column;
                }
            }catch (\Exception $e){
                $nestedCrumbs[$nested->getEditUrl()] = $nested->getBreadcrumbTitle();
            }
        }

        // Add the current list entry.
        $currentListUrl = $resource::getUrl(
            'index',
            $resource::getParentParametersForUrl($resource::getParent(), $this->urlParameters)
        );
        $nestedCrumbs[$currentListUrl] = $resource::getBreadcrumb();

        // Finalize with the current url.
        $breadcrumb = $this->getBreadcrumb();
        if (filled($breadcrumb)) {
            $nestedCrumbs[] = $breadcrumb;
        }

        return $nestedCrumbs;
    }

    /**
     * Handle Record Creation
     * It's a combination of the "handleRecordCreation()" function that exists in the "Filament\Resources\Pages\CreateRecord\Concerns\Translatable" and "SevendaysDigital\FilamentNestedResources\ResourcePages\NestedPage"
     *
     * @param array $data
     * @return Model
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = app(static::getModel());

        // "Translatable" Logic
        $record = app(static::getModel());
        $record->fill(Arr::except($data, $record->getTranslatableAttributes()));
        foreach (Arr::only($data, $record->getTranslatableAttributes()) as $key => $value) {
            $record->setTranslation($key, $this->activeFormLocale, $value);
        }

        // "NestedPage" Logic
        $resource = $this::getResource();
        $parent = Str::camel(Str::afterLast($resource::getParent()::getModel(), '\\'));
        $record->{$parent}()->associate($this->getParentId());

        $record->save();

        return $record;
    }
    protected function getTableQuery(): Builder
    {
        $urlParams = array_values($this->urlParameters);
        $parameter = array_pop($urlParams);

        return static::getResource()::getEloquentQuery($parameter);
    }

    protected function configureEditAction(\Filament\Pages\Actions\EditAction|EditAction $action): void
    {
        $resource = static::getResource();
        if ($action instanceof EditAction) {
            $action
                ->authorize(fn (Model $record): bool => $resource::canEdit($record))
                ->form(fn (): array => $this->getEditFormSchema());

            if ($resource::hasPage('edit')) {
                $action->url(fn (Model $record): string => $resource::getUrl(
                    'edit',
                    [...$this->urlParameters, 'record' => $record]
                ));
            }
        } else {
            $action
                ->authorize($resource::canEdit($this->getRecord()))
                ->record($this->getRecord())
                ->recordTitle($this->getRecordTitle());

            if ($resource::hasPage('edit')) {
                $action->url(fn (): string => static::getResource()::getUrl('edit', ['record' => $this->getRecord()]));

                return;
            }

            $action->form($this->getFormSchema());
        }

    }

    protected function configureCreateAction(CreateAction $action): void
    {
        $resource = static::getResource();

        $action
            ->authorize($resource::canCreate())
            ->model($this->getModel())
            ->modelLabel($this->getModelLabel())
            ->form(fn (): array => $this->getCreateFormSchema());

        if ($resource::hasPage('create')) {
            $action->url(fn (): string => $resource::getUrl('create', $this->urlParameters));
        }
    }

    protected function configureDeleteAction(DeleteAction|FilamentDeleteAction $action): void
    {
        $resource = static::getResource();
        $action
            ->authorize(fn (Model $record): bool => $resource::canDelete($record))
            ->record($this->getRecord())
            ->recordTitle($this->getRecordTitle())
            ->successRedirectUrl($resource::getUrl('index', $this->urlParameters));
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        if ($resource::hasPage('view') && $resource::canView($this->record)) {
            return $resource::getUrl('view', [...$this->urlParameters, 'record' => $this->record]);
        }

        if ($resource::hasPage('edit') && $resource::canEdit($this->record)) {
            return $resource::getUrl('edit', [...$this->urlParameters, 'record' => $this->record]);
        }

        return $resource::getUrl('index', $this->urlParameters);
    }

    protected function getParentId(): string|int
    {
        /** @var NestedResource $resource */
        $resource = $this::getResource();

        $parent = Str::camel(Str::afterLast($resource::getParent()::getModel(), '\\'));

        if ($this->urlParameters[$parent] instanceof Model) {
            return $this->urlParameters[$parent]->getKey();
        }

        if (is_array($this->urlParameters[$parent]) && isset($this->urlParameters[$parent]['id'])) {
            return $this->urlParameters[$parent]['id'];
        }

        return $this->urlParameters[$parent];
    }

    public function getParent(): Model
    {
        $resource = $this::getResource();

        return $resource::getParent()::getModel()::find($this->getParentId());
    }

    protected function form(Form $form): Form
    {
        return static::getResource()::form($form, $this->getParent());
    }
}
