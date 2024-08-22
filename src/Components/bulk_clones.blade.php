<a href="javascript:void(0)"
   onclick="btnBulkCloneEntries(this)"
   name="action"
   class="btn btn-default bulk-button" id="duplicate-button">
    <span class="fa fa-copy" role="presentation" aria-hidden="true"></span> &nbsp;
    <span>Duplicate</span>
</a>

@push('after_scripts') @if (request()->ajax())
    @endpush
@endif

<script>
    function btnBulkCloneEntries(button) {
        if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0) {
            return;
        }

        var message = ("Are you sure you want to duplicate the checked entries?").replace(":number", crud.checkedItems.length);

        if (confirm(message) == true) {
            var ajax_calls = [];
            var create_route = "{{ url($crud->route) }}/bulk-clones";

            $.ajax({
                url: create_route,
                type: 'POST',
                data: {
                    entries: crud.checkedItems,
                },
                beforeSend: function (xhr, settings) {
                    button.disabled = true;
                    $.LoadingOverlay("show");
                },
                success: function (result) {
                    $.LoadingOverlay("hide");

                    new PNotify({
                        title: result.status,
                        text: result.message,
                        type: result.status
                    });

                    button.disabled = false;
                    crud.checkedItems = [];
                    crud.table.ajax.reload(null, false);
                },
                error: function (result) {
                    button.disabled = false;

                    new PNotify({
                        title: "Failed!",
                        type: "warning"
                    });
                }
            });
        }
    }
</script>

@if (!request()->ajax())
    @endpush
@endif
