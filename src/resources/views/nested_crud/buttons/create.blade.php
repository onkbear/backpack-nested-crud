@if ($crud->hasAccess('create'))
  @php
    $modelReflection = new \ReflectionClass($crud->model);
  @endphp
  <button class="btn btn-sm btn-light" type="button" data-toggle="modal" data-target="#createModal{{ $modelReflection->getShortName() }}" data-remote="{{ url($crud->route.'/ajax/create') }}">
    <i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}
  </button>
@endif
