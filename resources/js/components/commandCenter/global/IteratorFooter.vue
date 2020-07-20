<template>
    <v-row no-gutters class="mt-2" align="center" justify="center">
        <div class="flex-grow-1"></div>
        <span class="mr-4 grey--text">Page {{ page }} of {{ numberOfPages }}</span>
        <v-btn
            fab
            dark
            x-small
            color="primary darken-1"
            class="mr-1 rh-dbl-tap-disabled"
            :disabled="formerPageDisabled"
            @click="emitFormerPage"
        >
            <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <v-btn
            fab
            dark
            x-small
            color="primary darken-1"
            class="ml-1 rh-dbl-tap-disabled"
            :disabled="nextPageDisabled"
            @click="emitNextPage"
        >
            <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
        <div class="flex-grow-1"></div>
    </v-row>
</template>

<script>
    export default {
        name: "IteratorFooter",
        props: {
            page: {
                type: Number,
                required: true
            },
            numberOfPages: {
                type: Number,
                required: true
            }
        },
        methods: {
            emitFormerPage() {
                this.$emit('formerPage')
            },
            emitNextPage() {
                this.$emit('nextPage')
            },
        },
        computed: {
            formerPageDisabled() {
                return this.page === 1;
            },
            nextPageDisabled() {
                return this.page === this.numberOfPages;
            },
        }
    }
</script>

<style scoped>

</style>
