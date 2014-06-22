
console.log('indexview');
App.IndexView = Em.View.extend({
    focusInput: function() {
        this.$('#main_search_input').focus();
    }.on('didInsertElement'),

    didInsertElement: function()
    {
        var self = this;
        console.log('indeinsert')
        var tpl = {
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                filter: function(list) {
                    return list.data;
                }
            }
        };
        tpl.remote.url = '/api/v1/autocomplete/states?q=%QUERY';
        var states = new Bloodhound(tpl);
        states.initialize();

        tpl.remote.url = '/api/v1/autocomplete/provinces?q=%QUERY';
        var provinces = new Bloodhound(tpl);
        provinces.initialize();

        tpl.remote.url = '/api/v1/autocomplete/cities?q=%QUERY';
        var cities = new Bloodhound(tpl);
        cities.initialize();

        this.$('#main_search_input').typeahead({
            highlight: true,
            minLength: 1
        },
        {
            name: 'states',
            displayKey: 'name',
            templates: {
                header: '<h3 class="type-name">States</h3>'
            },
            source: states.ttAdapter()
        },
        {
            name: 'provinces',
            displayKey: 'name',
            templates: {
                header: '<h3 class="type-name">Provinces</h3>'
            },
            source: provinces.ttAdapter()
        },
        {
            name: 'cities',
            displayKey: 'name',
            templates: {
                header: '<h3 class="type-name">Cities</h3>'
            },
            source: cities.ttAdapter()
        });

        this.$('#main_search_input').bind('typeahead:selected', function(obj, datum, name) {
            console.log(obj, datum, name);
            self.$('#main_search_input_real').val(datum.url).attr('name', name)
        });
    }
});
