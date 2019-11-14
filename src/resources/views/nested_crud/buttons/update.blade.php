@if ($crud->hasAccess('update'))
  @php
    $modelReflection = new \ReflectionClass($crud->model);
    $modalId = '#editModal'.$modelReflection->getShortName();
    $remoteUrl = url($crud->route.'/ajax/'.$entry->getKey().'/edit');
  @endphp

  @if (!$crud->model->translationEnabled())

    <!-- Single edit button -->
    <button
      class="btn btn-sm btn-link"
      type="button"
      data-toggle="modal"
      data-target="{{ $modalId }}"
      data-remote="{{ $remoteUrl }}"
      data-key="{{ $entry->getKey() }}"
    >
      <i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}
    </button>

  @else

    <!-- Edit button group -->
    <div class="btn-group">
      <button
        class="btn btn-sm btn-link pr-0"
        type="button"
        data-toggle="modal"
        data-target="{{ $modalId }}"
        data-remote="{{ $remoteUrl }}"
        data-key="{{ $entry->getKey() }}"
      >
        <i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}
      </button>
      <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
        @foreach ($crud->model->getAvailableLocales() as $key => $locale)
          <button
            class="dropdown-item"
            type="button"
            data-toggle="modal"
            data-target="{{ $modalId }}"
            data-remote="{{ $remoteUrl }}?locale={{ $key }}"
            data-key="{{ $entry->getKey() }}"
          >
            {{ $locale }}
          </button>
        @endforeach
      </ul>
    </div>

  @endif
@endif