@php
  $hasActions = $crud->buttons()->where('stack', 'line')->count();
@endphp
<table class="nested-list bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs" cellspacing="0">
  <thead>
    <tr>
      {{-- Table columns --}}
      @foreach ($crud->columns() as $column)
        <th>
          {!! $column['label'] !!}
        </th>
      @endforeach

      @if ($hasActions)
        <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}" data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @forelse ($entries as $entry)
      <tr class="nested-list-row">
        @foreach ($crud->columns() as $column)
          <td>
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
          </td>
        @endforeach
        @if ($hasActions)
          <td>
            @include('crud::inc.button_stack', ['stack' => 'line'])
          </td>
        @endif
      </tr>
    @empty
      <tr>
        <td colspan="{{ count($crud->columns()) + ($hasActions ? 1 : 0) }}" style="text-align:center;">{{ trans('backpack::crud.emptyTable') }}</td>
      </tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr>
      {{-- Table columns --}}
      @foreach ($crud->columns() as $column)
        <th>{!! $column['label'] !!}</th>
      @endforeach

      @if ($hasActions)
        <th>{{ trans('backpack::crud.actions') }}</th>
      @endif
    </tr>
  </tfoot>
</table>

{{-- Create button --}}
@include('nested_crud::buttons.create')
