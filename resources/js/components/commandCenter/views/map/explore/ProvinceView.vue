<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <MapCard :view-box="this._province.view_box">

            <!-- Borders -->
            <ProvinceVector
                v-for="(province, uuid) in this._borders"
                :key="uuid"
                :province="province"
                :route-link="true"
            >
            </ProvinceVector>

            <!-- Province -->
            <ProvinceVector :province="this._province" :highlight="true"></ProvinceVector>
        </MapCard>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import MapCard from "../../../map/MapCard";
    import ProvinceVector from "../../../map/ProvinceVector";

    export default {
        name: "ProvinceView",
        components: {
            MapCard,
            ProvinceVector
        },
        watch:{
            $route (to) {
                // this updates province if user hits back/forward through browser
                if (to.params.provinceSlug !== this._province.slug) {
                    this.setProvinceBySlug(to.params.provinceSlug);
                }
            }
        },
        methods: {
            ...mapActions([
                'setProvinceBySlug',
            ])
        },
        computed: {
            ...mapGetters([
                '_province',
                '_borders'
            ])
        }
    }
</script>

<style scoped>

</style>
