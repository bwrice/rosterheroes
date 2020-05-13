<template>
    <NavListItem
        :to="rosterRoute"
        :title="hero.name"
    ></NavListItem>
</template>

<script>
    import * as routerHelpers from "../../../helpers/routerHelpers"
    import Hero from "../../../models/Hero";
    import NavListItem from "./NavListItem";

    export default {
        name: "RosterHeroNavListItem",
        components: {NavListItem},
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },
        computed: {
            rosterRoute() {
                return routerHelpers.getRosterHeroRoute(this.hero, this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
