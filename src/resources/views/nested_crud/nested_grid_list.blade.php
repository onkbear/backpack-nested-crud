<div class="nested-list">
  @forelse ($entries as $entry)
  <div class="card nested-list-row">
    <div class="card-body bold-labels">
      <div class="row">
        @foreach ($crud->columns() as $column)

          <div @include('nested_crud::inc.column_wrapper_attributes')>
            <label>{!! $column['label'] !!}</label>

            <div>
              @if (!isset($column['type']))
                @include('crud::columns.text')
              @else
                @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
                  @include('vendor.backpack.crud.columns.'.$column['type'])
                @else
                  @if(view()->exists('crud::columns.'.$column['type']))
                    @include('crud::columns.'.$column['type'])
                  @else
                    @include('crud::columns.text')
                  @endif
                @endif
              @endif
            </div>
          </div>

        @endforeach

        @if ($crud->buttons()->where('stack', 'line')->count())
          <div class="col-sm-12">
              <label>{{ trans('backpack::crud.actions') }}</label>
            <div>
              @include('crud::inc.button_stack', ['stack' => 'line'])
            </div>
          </div>
        @endif

      </div>
    </div>
  </div>
  @empty
    <p>{{ trans('backpack::crud.emptyTable') }}</p>
  @endforelse
</div>

{{-- Create button --}}
@include('nested_crud::buttons.create')
