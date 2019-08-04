<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <Realm>
            <ProvinceVector :province="this._province"></ProvinceVector>
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
                '_province'
            ])
        }
    }
</script>

<style scoped>

</style>
