<template>
    <v-card>
        <v-card-title>{{hero.name}}</v-card-title>
        <v-card-actions>
            <v-btn :to="barracksHeroRoute">Edit</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "BarracksHeroCard",
        props: {
            hero: {
                type: Object,
                require: true
            }
        },
        computed: {
            barracksHeroRoute() {
                let squadSlugParam = this.$route.params.squadSlug;
                return {
                    name: 'barracks-hero',
                    params: {
                        squadSlug: squadSlugParam,
                        heroSlug: this.hero.slug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
