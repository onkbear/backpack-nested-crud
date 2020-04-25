<?php

namespace Onkbear\NestedCrud\app\Http\Controllers\Operations;

use Illuminate\Support\Facades\Route;

trait NestedUpdateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $name       Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupNestedUpdateRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/ajax/{id}/edit', [
            'as'        => $routeName.'.nestedEdit',
            'uses'      => $controller.'@nestedEdit',
            'operation' => 'nestedUpdate',
        ]);

        Route::put($segment.'/ajax/{id}', [
            'as'        => $routeName.'.nestedUpdate',
            'uses'      => $controller.'@nestedUpdate',
            'operation' => 'nestedUpdate',
        ]);

        Route::get($segment.'/ajax/{id}/translate/{lang}', [
            'as'        => $routeName.'.nestedTranslateItem',
            'uses'      => $controller.'@nestedTranslateItem',
            'operation' => 'nestedUpdate',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupNestedUpdateDefaults()
    {
        $this->crud->allowAccess('update');

        $this->crud->operation('nestedUpdate', function () {
            $this->crud->setUpdateView('nested_crud::modals.edit');

            if ($this->crud->getModel()->translationEnabled()) {
                $this->crud->addField([
                    'name' => 'locale',
                    'type' => 'hidden',
                    'value' => $request()->input('locale') ?? app()->getLocale(),
                ]);
            }

            $this->crud->setupDefaultSaveActions();
        });

        $this->crud->operation(['nestedList'], function () {
            $this->crud->addButton('line', 'nested_update', 'view', 'nested_crud::buttons.update', 'end');
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function nestedEdit($id)
    {
        $this->crud->hasAccessOrFail('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    /**
     * Update the specified resource in the database.
     *
     * @return string
     */
    public function nestedUpdate()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest()
        );
        $this->data['entry'] = $this->crud->entry = $item;

        return $item->getKey();
    }
}
