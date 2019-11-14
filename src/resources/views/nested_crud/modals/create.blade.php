@extends('nested_crud::modals.layout')

@section('header')
  <h4 class="modal-title">{{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</h4>
@endsection

@section('content')
  <div class="bold-labels">
    <!-- Default box -->
    @include('crud::inc.grouped_errors')
    <!-- load the view from the application if it exists, otherwise load the one in the package -->
    @if(view()->exists('vendor.backpack.crud.form_content'))
      @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
    @else
      @include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
    @endif
  </div>
@endsection

@section('footer')
  <button class="btn btn-secondary" type="button" data-dismiss="modal"><span class="fa fa-ban"></span> {{ trans('backpack::crud.cancel') }}</button>
  <button class="btn btn-primary" type="submit"><span class="fa fa-save" role="presentation" aria-hidden="true"></span> {{ trans('backpack::crud.save') }}</button>
@endsection
