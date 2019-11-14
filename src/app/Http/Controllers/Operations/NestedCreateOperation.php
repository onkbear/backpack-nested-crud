<?php

namespace Onkbear\NestedCrud\app\Http\Controllers\Operations;

use Illuminate\Support\Facades\Route;

trait NestedCreateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupNestedCreateRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/ajax/create', [
            'as'        => $routeName.'.nestedCreate',
            'uses'      => $controller.'@nestedCreate',
            'operation' => 'nestedCreate',
        ]);

        Route::put($segment.'/ajax/create', [
            'as'        => $routeName.'.nestedStore',
            'uses'      => $controller.'@nestedStore',
            'operation' => 'nestedCreate',
        ]);

        Route::post($segment.'/ajax', [
            'as'        => $routeName.'.nestedStore',
            'uses'      => $controller.'@nestedStore',
            'operation' => 'nestedCreate',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupNestedCreateDefaults()
    {
        $this->crud->allowAccess('create');

        $this->crud->operation('nestedCreate', function () {
            $this->crud->setCreateView('nested_crud::modals.create');
        });
    }

    /**
     * Show the form for creating inserting a new row.
     *
     * @return Response
     */
    public function nestedCreate()
    {
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getCreateView(), $this->data);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return string
     */
    public function nestedStore()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        return $item->getKey();
    }
}
