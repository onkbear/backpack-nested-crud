@extends('nested_crud::modals.layout')

@section('header')
  <h4 class="modal-title">{{ trans('backpack::crud.edit') }} {{ $crud->entity_name }}</h4>
@endsection

@section('content')
  <div class="bold-labels">
    <!-- Default box -->
    @include('crud::inc.grouped_errors')
    <!-- load the view from the application if it exists, otherwise load the one in the package -->
    @if(view()->exists('vendor.backpack.crud.form_content'))
      @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'edit' ])
    @else
      @include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'edit' ])
    @endif
  </div>
@endsection

@section('footer')
  <div class="save-block">
    <button class="btn btn-secondary" type="button" data-dismiss="modal"><span class="fa fa-ban"></span> {{ trans('backpack::crud.cancel') }}</button>
    <button class="btn btn-primary" type="button" id="saveButton"><span class="fa fa-save" aria-hidden="true"></span> {{ trans('backpack::crud.save') }}</button>
  </div>
@endsection
