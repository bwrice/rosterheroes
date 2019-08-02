
export const continentMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        provincesForContinent() {
            let continentProvinces = [];
            let self = this;
            this._provinces.forEach(function(province) {
                if (province.continent_id === self.continent.id) {
                    continentProvinces.push(province);
                }
            });
            return continentProvinces;
        },
    },
    watch: {
        //
    },
    methods: {
        //
    }
};