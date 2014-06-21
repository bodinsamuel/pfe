$(document).ready(function() {
    $("#main_search_input").select2({
        cacheDataSource: [],
        placeholder: "Select a State",
        allowClear: true,
        multiple: true,
        minimumInputLength: 2,
        maximumSelectionSize: 1,
        query: function(query) {
            self = this;
            var key = query.term;
            var cachedData = self.cacheDataSource[key];

            if (cachedData) {
                query.callback({
                    results: cachedData
                });
                return;
            } else {
                $.ajax({
                    url: "/api/v1/autocomplete/location",
                    data: {
                        q : query.term
                    },
                    dataType: 'json',
                    type: 'GET',
                    success: function(json) {
                        self.cacheDataSource[key] = format_result(json.data);
                        query.callback({
                            results: self.cacheDataSource[key]
                        });
                    }
                });
            }
        },
        formatResult: format_atc_location,
        formatSelection: format_atc_location,
        id: function(object)
        {
            return object.url;
        }
    });

    $("#main_search_input").on('change', function(e) {
        // e.stopPropagation();
        // e.preventDefault();
        var results = $(this).select2('data');
        if (results)
        {
            var parsed = {
                'cities': null,
                'states': null,
                'provinces': null,
            };
            for (var i = 0; i < results.length; i++)
            {
                if (!parsed[results[i]['type']])
                    parsed[results[i]['type']] = results[i]['url'];
                else
                    parsed[results[i]['type']] += ',' + results[i]['url'];
            };

            $('#main_search_input_cities').val(parsed.cities);
            $('#main_search_input_states').val(parsed.states);
            $('#main_search_input_provinces').val(parsed.provinces);
        }
        console.log(results, parsed);

        // return false;
    });

    function format_atc_location (state)
    {
        return state.name;
    }

    function format_result (data) 
    {
        var formatted = [];
        for (var i = 0; i < data.length; i++) {
            if (data[i].children.length > 0)
                formatted.push(data[i]);
        };
            console.log(formatted)
        return formatted;
    }
});
