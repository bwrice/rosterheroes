<template>
    <CardSection :title="'MERCHANTS'">
        <template v-if="_localMerchants.length > 0">
            <MerchantPanel
                v-for="(merchant, uuid) in _localMerchants"
                :key="uuid"
                :merchant="merchant"
            ></MerchantPanel>
        </template>
        <template v-else>
            <EmptyNotifier :notification-text="'No local merchants'"></EmptyNotifier>
        </template>
    </CardSection>
</template>

<script>

    import {mapGetters} from 'vuex';

    import CardSection from "../global/CardSection";
    import EmptyNotifier from "../global/EmptyNotifier";
    import MerchantPanel from "../campaign/MerchantPanel";
    export default {
        name: "LocalMerchants",
        components: {MerchantPanel, EmptyNotifier, CardSection},
        computed: {
            ...mapGetters([
                '_localMerchants'
            ])
        }
    }
</script>

<style scoped>

</style>
