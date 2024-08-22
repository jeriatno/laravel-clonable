<a href="javascript:void(0)"
   onclick="btnCloneEntry({{ $entry->id }})"
   class="btn btn-xs btn-default"
   data-button-type="clone">
    <i class="fa fa-clone"></i> Duplicate
</a>

@push('after_scripts') @if (request()->ajax())
    @endpush
@endif

<script>
    function btnCloneEntry(itemId) {
        $.ajax({
            type: "POST",
            url: "{{ url($crud->route) }}/clones/" + itemId,
            dataType: 'json',
            cache: false,
            success: function (response) {
                if (response.status == 'success') {
                    new PNotify({
                        title: response.status,
                        text: response.message,
                        type: response.status
                    });

                    crud.table.ajax.reload(null, false);
                } else {
                    new PNotify({
                        title: response.status,
                        text: response.message,
                        type: response.status
                    });
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: "Error!",
                    text: error,
                    type: "warning"
                });
            }
        });
    }

</script>

@if (!request()->ajax())
    @endpush
@endif
