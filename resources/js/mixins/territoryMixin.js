
export const territoryMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        provincesForTerritory() {
            let territoryProvinces = [];
            let self = this;
            this._provinces.forEach(function(province) {
                if (province.territory_id === self.territory.id) {
                    territoryProvinces.push(province);
                }
            });
            return territoryProvinces;
        },
    },
    watch: {
        //
    },
    methods: {
        //
    }
};