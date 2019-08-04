<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <Realm :view-box="this._province.view_box">
            <ProvinceVector :province="this._province"></ProvinceVector>
            <ProvinceVector
                v-for="(province, uuid) in this._borders"
                :key="uuid"
                :province="province"
            >
            </ProvinceVector>
        </Realm>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import Realm from "../../map/Realm";
    import ProvinceVector from "../../map/ProvinceVector";

    export default {
        name: "ProvinceView",
        components: {ProvinceVector, Realm},
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
