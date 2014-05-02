$(document).ready(function() {
    $("#main_search_input").select2({
        cacheDataSource: [],
        placeholder: "Select a State",
        allowClear: true,
        multiple: true,
        minimumInputLength: 2,
        ajax: {
            url: "/api/v0/autocomplete/location",
            dataType: 'json',
            quietMillis: 100,
            cache: true,
            data: function (term, page) { // page is the one-based page number tracked by Select2
                return {q: term}
            },
            results: function (json, page) {
                return {results: json.data.cities};
            }
        },
        query: function(query) {
            self = this;
            var key = query.term;
            var cachedData = self.cacheDataSource[key];

            if (cachedData) {
                query.callback({results: cachedData.data.cities});
                return;
            } else {
                $.ajax({
                    url: "/api/v0/autocomplete/location",
                    data: { q : query.term },
                    dataType: 'json',
                    type: 'GET',
                    success: function(json) {
                        self.cacheDataSource[key] = json;
                        query.callback({results: json.data.cities});
                    }
                });
            }
        },
        formatResult: format_atc_location,
        formatSelection: format_atc_location,
    });

    function format_atc_location (state)
    {
        return state.name;
    }
});
