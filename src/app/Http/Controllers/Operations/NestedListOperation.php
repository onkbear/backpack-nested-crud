<?php

namespace Onkbear\NestedCrud\app\Http\Controllers\Operations;

use Illuminate\Support\Facades\Route;

trait NestedListOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupNestedListRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/ajax', [
            'as'        => $routeName.'.nestedList',
            'uses'      => $controller.'@nestedList',
            'operation' => 'nestedList',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupNestedListDefaults()
    {
        $this->crud->allowAccess('list');

        $this->crud->operation('nestedList', function () {
            $this->crud->setListView('nested_crud::nested_list');
        });
    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return \Illuminate\View\View
     */
    public function nestedList()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['entries'] = $this->crud->getEntries();
        $this->data['crud'] = $this->crud;

        return view($this->crud->getListView(), $this->data);
    }
}
