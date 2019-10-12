<template>
    <v-btn
        block
        large
        :to="heroRoute"
        color="primary"
    >
        Add Spirit
    </v-btn>
</template>

<script>
    export default {
        name: "AddSpiritRouterButton",
        props: {
            heroSlug: {
                type: String,
                required: true
            }
        },
        computed: {
            heroRoute() {
                return {
                    name: 'roster-hero',
                    params: {
                        heroSlug: this.heroSlug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
