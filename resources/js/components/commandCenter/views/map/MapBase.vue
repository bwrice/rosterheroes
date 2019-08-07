<template>
    <BaseView></BaseView>
</template>

<script>
    import { mapActions } from 'vuex'

    import Squad from "../../../../models/Squad";
    import Province from "../../../../models/Province";
    import BaseView from "../BaseView";

    export default {
        name: "MapBase",
        components: {BaseView},

        async mounted() {
            let squadSlug = this.$route.params.squadSlug;
            let squad = new Squad({slug: squadSlug});
            let currentLocation = await Province.custom(squad, 'current-location').$first();
            this.setCurrentLocation(currentLocation);
        },

        methods: {
            ...mapActions([
                'setCurrentLocation'
            ])
        }

    }
</script>

<style scoped>

</style>
