@if ($crud->getCurrentOperation() === 'update')

@php
  $routeBase        = url($crud->route.'/'.$entry->getKey().'/'.$field['target']);
  $modelReflection  = new \ReflectionClass($field['model']);
  $createModalId    = 'createModal'.$modelReflection->getShortName();
  $editModalId      = 'editModal'.$modelReflection->getShortName();
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
  <label>{!! $field['label'] !!}</label>

  {{-- List view --}}
  <div
    id="{{ $field['name'] }}"
    data-init-function="bpFieldInitNestedCrudFromAjaxElement"
    data-remote="{{ $routeBase.'/ajax' }}"
  >
  </div>

@push('crud_fields_styles')
  {{-- This tag is the mark for applying styles from modal contents --}}
  <div id="nested-crud-fields-styles"></div>
@endpush

@push('crud_fields_scripts')
  {{-- This tag is the mark for applying scripts from modal contents --}}
  <div id="nested-crud-fields-scripts"></div>

  {{-- Modal for create --}}
  <div class="modal fade" id="{{ $createModalId }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form method="post"
        action="{{ $routeBase.'/ajax/create' }}"
        id="{{ $createModalId }}Form"
        @if ($crud->hasUploadFields('create'))
        enctype="multipart/form-data"
        @endif
      >
        {!! csrf_field() !!}
        <div class="modal-content">
          <div class="text-center">
            <div><span class="fa fa-spinner fa-spin"></span> Loading...</div>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal for edit --}}
  <div class="modal fade" id="{{ $editModalId }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form method="post"
        action=""
        id="{{ $editModalId }}Form"
        @if ($crud->hasUploadFields('update', $entry->getKey()))
        enctype="multipart/form-data"
        @endif
      >
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}
        <div class="modal-content">
          <div><span class="fa fa-spinner fa-spin"></span> Loading...</div>
        </div>
      </form>
    </div>
  </div>
@endpush

  {{-- HINT --}}
  @if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
  @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
<!-- include field specific nested_crud js-->
@push('crud_fields_scripts')
<script>
  function getNestedCrudLoadingHtml() {
    return '<div class="text-center"><div><span class="fa fa-spinner fa-spin"></span> Loading...</div></div>';
  }

  function loadNestedCrudList(element) {
    element.html(getNestedCrudLoadingHtml());
    $.ajax({
      url: element.data('remote'),
      type: 'GET',
      success: function(data){
        element.html($(data));
      }
    });
  }

  function bpFieldInitNestedCrudFromAjaxElement(element) {
    loadNestedCrudList(element);
  }

  (function () {
    function highlightErrors(errors) {

      $.each(errors, function (property, messages) {

        var normalizedProperty = property.split('.').map(function(item, index) {
          return index === 0 ? item : '['+item+']';
        }).join('');

        var field = $('[name="' + normalizedProperty + '[]"]').length ?
                    $('[name="' + normalizedProperty + '[]"]') :
                    $('[name="' + normalizedProperty + '"]'),
                    container = field.parents('.form-group');

        container.addClass('text-danger');
        container.children('input, textarea').addClass('is-invalid');

        $.each(messages, function(key, msg){
          // highlight the input that errored
          var row = $('<div class="invalid-feedback">' + msg + '</div>');
          row.appendTo(container);
        });
      });
    }

    $('#{{ $createModalId }}Form').submit(function (e) {
      e.preventDefault();

      var formElement = $('#{{ $createModalId }}Form');

      $.ajax({
        type: 'PUT',
        url: formElement.attr('action'),
        data: formElement.serialize(),
        success: function (data) {
          new Noty({
            type: "success",
            text: "{{ trans('backpack::crud.insert_success') }}"
          }).show();

          loadNestedCrudList($('#{{ $field['name'] }}'));
          $('#{{ $createModalId }}').modal('hide');
        },
        error: function (data) {
          new Noty({
            type: "error",
            text: "{{ trans('backpack::base.error') }}: " + data.responseJSON.message
          }).show();

          highlightErrors(data.responseJSON.errors);
        }
      });
    });

    $('#{{ $editModalId }}Form').submit(function (e) {
      e.preventDefault();

      var formElement = $('#{{ $editModalId }}Form');

      $.ajax({
        type: 'PUT',
        url: formElement.attr('action'),
        data: formElement.serialize(),
        success: function (data) {
          new Noty({
            type: "success",
            text: "{{ trans('backpack::crud.update_success') }}"
          }).show();

          loadNestedCrudList($('#{{ $field['name'] }}'));
          $('#{{ $editModalId }}').modal('hide');
        },
        error: function (data) {
          new Noty({
            type: "error",
            text: "{{ trans('backpack::base.error') }}: " + data.responseJSON.message
          }).show();

          highlightErrors(data.responseJSON.errors);
        }
      });
    });

    // Load modal content
    $('#{{ $createModalId }}').on('show.bs.modal', function (e) {
      var button = $(e.relatedTarget);
      $('#{{ $createModalId }} .modal-content').html(getNestedCrudLoadingHtml());
      $.ajax({
        url: button.data('remote'),
        type: 'GET',
        success: function(data){
          $('#{{ $createModalId }} .modal-content').html($(data).closest('#modal-content'));

          // Apply styles and scripts from modal contents
          $('#nested-crud-fields-styles').after($(data).closest('#nested-crud-fields-styles').html());
          $('#nested-crud-fields-scripts').after($(data).closest('#nested-crud-fields-scripts').html());

          // trigger the javascript for all fields that have their js defined in a separate method
          initializeFieldsWithJavascript('#{{ $createModalId }} form');
        }
      });
    });

    $('#{{ $editModalId }}').on('show.bs.modal', function (e) {
      var button = $(e.relatedTarget);
      $('#{{ $editModalId }} .modal-content').html(getNestedCrudLoadingHtml());
      $.ajax({
        url: button.data('remote'),
        type: 'GET',
        success: function(data){
          var key = button.data('key');
          $('#{{ $editModalId }} .modal-content').html($(data));
          $('#{{ $editModalId }}Form').attr('action', '{{ $routeBase.'/ajax/' }}' + key)

          // Apply styles and scripts from modal contents
          $('#nested-crud-fields-styles').after($(data).closest('#nested-crud-fields-styles').html());
          $('#nested-crud-fields-scripts').after($(data).closest('#nested-crud-fields-scripts').html());

          // trigger the javascript for all fields that have their js defined in a separate method
          initializeFieldsWithJavascript('#{{ $editModalId }} form');
        }
      });
    });
  })();
</script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}

@endif
