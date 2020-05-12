<template>
    <NavListItem
        :to="barracksRoute"
        :title="hero.name"
    ></NavListItem>
</template>

<script>
    import * as routerHelpers from "../../helpers/routerHelpers"
    import Hero from "../../models/Hero";
    import NavListItem from "./NavListItem";

    export default {
        name: "BarracksHeroNavListItem",
        components: {NavListItem},
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },
        computed: {
            barracksRoute() {
                return routerHelpers.getBarracksHeroRoute(this.hero, this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
