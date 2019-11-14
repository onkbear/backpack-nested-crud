# Backpack Nested Crud

This package gives nested CRUD operation on your edit page.

Inspired by [Nested resources in Backpack CRUD](https://backpackforlaravel.com/articles/tutorials/nested-resources-in-backpack-crud)

E.g.

Gives the avility of CRUD operation of `comment` model as a field on `user` CRUD.

Only supports [Laravel-Backpack/CRUD](https://github.com/Laravel-Backpack/CRUD) v4.

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
