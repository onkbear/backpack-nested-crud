<?php

namespace Onkbear\NestedCrud\app\Http\Controllers\Operations;

use Illuminate\Support\Facades\Route;

trait NestedDeleteOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $name       Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupNestedDeleteRoutes($segment, $routeName, $controller)
    {
        Route::delete($segment.'/ajax/{id}', [
            'as'        => $routeName.'.nestedDestroy',
            'uses'      => $controller.'@nestedDestroy',
            'operation' => 'nestedDelete',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupNestedDeleteDefaults()
    {
        $this->crud->allowAccess('delete');

        $this->crud->operation(['nestedList'], function () {
            $this->crud->addButton('line', 'nested_delete', 'view', 'nested_crud::buttons.delete', 'end');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function nestedDestroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        return $this->crud->delete($id);
    }
}
