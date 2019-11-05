<template>
    <SvgIconSheet
        :color="'#C8DDE0'"
        :svg="heroRace.svg"
        :classes-object="{'pa-1': true}"
    >
    </SvgIconSheet>
</template>

<script>
    import SvgIconSheet from "./SvgIconSheet";
    import HeroRace from "../../../models/HeroRace";

    export default {
        name: "HeroRaceIcon",
        components: {SvgIconSheet},
        props: {
            heroRace: {
                type: HeroRace,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
