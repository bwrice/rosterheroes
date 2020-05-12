<template>
    <v-list-item :to="barracksRoute" color="accent">
        <span class="font-weight-light">{{hero.name}}</span>
    </v-list-item>
</template>

<script>
    import * as routerHelpers from "../../helpers/routerHelpers"
    import Hero from "../../models/Hero";

    export default {
        name: "BarracksHeroNavListItem",
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },
        methods: {
            goToBarracksRoute() {
                this.$router.push(this.barracksRoute);
            }
        },
        computed: {
            barracksRoute() {
                return routerHelpers.getBarracksHeroRoute(this.hero, this.$route);
            },
            isCurrentRoute() {
                return routerHelpers.routesMatch(this.$route, this.barracksRoute);
            }
        }
    }
</script>

<style scoped>

</style>
