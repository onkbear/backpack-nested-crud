@if (isset($column['wrapperAttributes']))
  @if (!isset($column['wrapperAttributes']['class']))
    class="form-group col-sm-12"
  @else
    class="{{ $column['wrapperAttributes']['class'] }}"
  @endif

  @php
    unset($column['wrapperAttributes']['class']);
  @endphp

  @foreach ($column['wrapperAttributes'] as $attribute => $value)
    @if (is_string($attribute))
      {{ $attribute }}="{{ $value }}"
    @endif
  @endforeach
@else
  class="form-group col-sm-12"
@endif
