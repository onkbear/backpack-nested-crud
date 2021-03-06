@if ($crud->hasAccess('delete'))
  <a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="{{ url($crud->route.'/ajax/'.$entry->getKey()) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

  if (typeof deleteEntry != 'function') {
    $("[data-button-type=delete]").unbind('click');

    function deleteEntry(button) {
      // ask for confirmation before deleting an item
      // e.preventDefault();
      var button = $(button);
      var route = button.attr('data-route');
      var row = $(".nested-list a[data-route='"+route+"']").closest('.nested-list-row');

      swal({
        title: "{!! trans('backpack::base.warning') !!}",
        text: "{!! trans('backpack::crud.delete_confirm') !!}",
        icon: "warning",
        buttons: {
          cancel: {
            text: "{!! trans('backpack::crud.cancel') !!}",
            value: null,
            visible: true,
            className: "bg-secondary",
            closeModal: true,
          },
          delete: {
            text: "{!! trans('backpack::crud.delete') !!}",
            value: true,
            visible: true,
            className: "bg-danger",
          }
        },
      }).then((value) => {
        if (value) {
          $.ajax({
            url: route,
            type: 'DELETE',
            success: function(result) {
              if (result != 1) {
                // Show an error alert
                swal({
                  title: "{!! trans('backpack::crud.delete_confirmation_not_title') !!}",
                  text: "{!! trans('backpack::crud.delete_confirmation_not_message') !!}",
                  icon: "error",
                  timer: 2000,
                  buttons: false,
                });
              } else {
                // Show a success message
                swal({
                  title: "{!! trans('backpack::crud.delete_confirmation_title') !!}",
                  text: "{!! trans('backpack::crud.delete_confirmation_message') !!}",
                  icon: "success",
                  timer: 4000,
                  buttons: false,
                });

                // Hide the modal, if any
                $('.modal').modal('hide');

                // Remove the row from the datatable
                row.remove();
              }
            },
            error: function(result) {
              // Show an alert with the result
              swal({
                title: "{!! trans('backpack::crud.delete_confirmation_not_title') !!}",
                text: "{!! trans('backpack::crud.delete_confirmation_not_message') !!}",
                icon: "error",
                timer: 4000,
                buttons: false,
              });
            }
          });
        }
      });
    }
  }

  // make it so that the function above is run after each DataTable draw event
  // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
@if (!request()->ajax()) @endpush @endif