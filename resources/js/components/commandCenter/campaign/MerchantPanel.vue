<template>
    <v-sheet
        color="#5c707d"
        class="rh-clickable mb-1 rounded"
        elevation="4"
        @click.native="handleClick"
    >
        <v-row no-gutters justify="space-between" align="center" class="px-4 py-2">
            <v-icon
                large
                :color="merchant.iconColor"
            >{{merchant.iconName}}</v-icon>
            <v-spacer></v-spacer>
            <span class="title rh-op-90">{{merchant.name}}</span>
            <v-spacer></v-spacer>
        </v-row>
    </v-sheet>
</template>

<script>
    import Merchant from "../../../models/Merchant";

    export default {
        name: "MerchantPanel",
        props: {
            merchant: {
                type: Merchant,
                required: true
            }
        },
        methods: {
            handleClick() {
                this.$router.push(this.merchant.getRoute(this.$route.params.squadSlug));
            }
        }
    }
</script>

<style scoped>

</style>
