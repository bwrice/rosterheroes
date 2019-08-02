<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-card>
            <Realm>
                <ProvinceVector v-for="(province, uuid) in this._provinces" :key="uuid" :province="province"></ProvinceVector>
            </Realm>
        </v-card>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ProvinceVector from "./ProvinceVector";
    import Realm from "./Realm";

    export default {
        name: "MapMain",
        components: {Realm, ProvinceVector},

        data: function() {
            return {
                mode: 'continent'
            }
        },
        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents'
            ])
        }
    }
</script>

<style scoped>

</style>