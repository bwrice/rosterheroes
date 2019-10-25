import {mapGetters} from "vuex";

export const travelMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        ...mapGetters([
            '_currentLocationProvince',
            '_routePosition',
            '_provinces',
            '_travelRoute'
        ]),
    },
    watch: {
        //
    },
    methods: {
        minimMapProvinceColor(province) {
            if (province.uuid === this._currentLocationProvince.uuid) {
                return '#dd00ff';
            } else if (province.uuid === this._routePosition.uuid) {
                return '#4ef542';
            } else if (this.provinceInRoute(province)) {
                return '#035afc'
            }
            return '#dedede';
        },
        provinceInRoute(province) {
            let value = false;
            this._travelRoute.forEach(function (routeProvince) {
                if (routeProvince.uuid === province.uuid) {
                    value = true;
                }
            });
            return value;
        },
    }
};
