<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#sendstatus').select2({
            ajax: {
                url: '{{'/selectmoresendstatuses'}}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.titre
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250
            },
            placeholder: 'Statut Envoi',
//                minimumInputLength: 2,
            multiple: true
        });
    });
</script>
