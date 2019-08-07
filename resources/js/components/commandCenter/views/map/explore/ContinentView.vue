<template>
    <MapCard :view-box="viewBox">
        <ProvinceVector
            v-for="(province, uuid) in provincesForContinent"
            :key="uuid"
            :province="province"
            :route-link="true"
        ></ProvinceVector>
    </MapCard>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import { continentMixin } from '../../../../../mixins/continentMixin';
    import MapCard from "../../../map/MapCard";
    import ProvinceVector from "../../../map/ProvinceVector";

    export default {
        name: "ContinentView",
        components: {ProvinceVector, MapCard},
        mixins: [
            continentMixin
        ],

        watch:{
            $route (to) {
                // this updates continent if user hits back/forward through browser
                if (to.params.continentSlug !== this._territory.continentSlug) {
                    this.setContinentBySlug(to.params.continentSlug);
                }
            }
        },

        methods: {
            ...mapActions([
                'setContinentBySlug',
            ])
        },

        computed: {
            ...mapGetters([
                '_provinces',
                '_continent',
            ]),
            // needed for continent mixin
            continent() {
                return this._continent;
            },
            viewBox() {
                return this._continent.realm_view_box;
            }
        }
    }
</script>

<style scoped>

</style>
