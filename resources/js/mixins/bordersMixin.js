import {mapGetters} from "vuex";

export const bordersMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        ...mapGetters([
            '_provinces'
        ]),
        borders() {
            let borderUuids = this.province.borders.map((border) => border.uuid);
            return this._provinces.filter(function(province) {
                return borderUuids.includes(province.uuid);
            })
        }
    },
    watch: {
        //
    },
    methods: {
        //
    }
};
