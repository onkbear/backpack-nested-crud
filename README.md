# Backpack Nested Crud

[![Latest Stable Version](https://img.shields.io/packagist/v/onkbear/backpack-nested-crud.svg?style=flat-square)](https://packagist.org/packages/onkbear/backpack-nested-crud)
[![Software License](https://img.shields.io/github/license/onkbear/backpack-nested-crud?style=flat-square)](LICENSE)

This package gives nested CRUD operations on your edit page.

Inspired by [Nested resources in Backpack CRUD](https://backpackforlaravel.com/articles/tutorials/nested-resources-in-backpack-crud)

E.g.

Gives the avility of CRUD operations of `comment` model as a field on `user` edit page.

For [Laravel-Backpack/CRUD](https://github.com/Laravel-Backpack/CRUD) v4.0, please use `^1.1`.

![Demo](https://user-images.githubusercontent.com/6011203/71384896-04b19080-25dc-11ea-9c97-6ee38d31619c.gif)

## Install

``` bash
composer require onkbear/backpack-nested-crud
```

## Usage

There are four (instead of create, read, update, delete) nested CRUD operations.

``` php
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedListOperation;
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedCreateOperation;
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedUpdateOperation;
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedDeleteOperation;
```

## Example

Please create `User` model and `Comment` model with relationship.

Please create `UserCrudController`.

```php
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel('App\Models\User');
        CRUD::setRoute(config('backpack.base.route_prefix').'/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        CRUD::addColumns(['name']);
    }

    protected function setupCreateOperation()
    {
        CRUD::addField([
            'name'  => 'name',
            'label' => 'Name',
            'type'  => 'text',
            'tab'   => 'Texts',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        CRUD::addField([
            'name'      => 'comments',
            'label'     => 'Comment',
            'type'      => 'nested_crud',
            'target'    => 'comment',
            'model'     => 'App\Models\Comment',
            'tab'       => 'Comments',  // optional
        ]);
    }
}

```

### Create the CRUD controller

``` php
use App\Http\Requests\CommentRequest as StoreRequest;
use App\Http\Requests\CommentRequest as UpdateRequest;

class UserCommentCrudController extends CrudController
{
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedListOperation;
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedCreateOperation;
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedUpdateOperation;
    use \Onkbear\NestedCrud\app\Http\Controllers\Operations\NestedDeleteOperation;

    public function setup()
    {
        // ...

        // get the user_id parameter
        $user_id = \Route::current()->parameter('user_id');

        // set a different route for the admin panel buttons
        CRUD::setRoute(config('backpack.base.route_prefix').'/user/'.$user_id.'/comment');

        // show only that user's comments
        CRUD::addClause('where', 'user_id', $user_id);

        // ...
    }

    protected function setupNestedListOperation()
    {
        // ...
    }

    protected function setupNestedCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        // ...
    }

    protected function setupNestedUpdateOperation()
    {
        CRUD::setValidation(UpdateRequest::class);

        // ...
    }
```

### Add the route

Setup the route in `route/custom.php`

``` php
    Route::crud('user/', 'UserCrudController');
    Route::group(['prefix' => 'user/{user_id}'], function() {
        Route::crud('comment', 'UserCommentCrudController');
    });
```

You have following routes.

- `admin/user/`
- `admin/user/{user_id}/comment/`

That's it.

You can also use backpack operations into `UserCommentCrudController` or extend `CommentCrudController` if it is exist.

## Customize views for list view

There are two templates for list view.

- `nested_crud::nested_list` : table view (default)
- `nested_crud::nested_grid_list` : grid view

![Grid View](https://user-images.githubusercontent.com/6011203/71385071-15163b00-25dd-11ea-9b99-880d8f52af67.png)

If you would like to use grid view, simply use the set method below.

``` php
// UserCommentCrudController.php

    protected function setupNestedListOperation()
    {
        $this->crud->setListView('nested_crud::nested_grid_list');
    }
```

If you created view files with exact same name in `resources/views/vendor/backpack/nested_crud` folder, it will override.
