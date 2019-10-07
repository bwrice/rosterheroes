<template>
    <BaseView>
        <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" offset-lg="2" lg="4" offset-xl="3" xl="3">
            <slot name="column-one"></slot>
        </v-col>
        <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="4" xl="3">
            <slot name="column-two"></slot>
        </v-col>
    </BaseView>
</template>

<script>
    import BaseView from "../views/BaseView";
    export default {
        name: "TwoColumnView",
        components: {BaseView},
    }
</script>

<style scoped>

</style>
