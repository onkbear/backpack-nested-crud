<div id="nested-crud-fields-styles">
@stack('crud_fields_styles')
</div>

<div id="modal-content">
  <div class="modal-header">
    @yield('header')
    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
  </div>
  <div class="modal-body">
    @yield('content')
  </div>
  <div class="modal-footer">
    @yield('footer')
  </div>
</div>

<div id="nested-crud-fields-scripts">
@stack('crud_fields_scripts')
</div>
