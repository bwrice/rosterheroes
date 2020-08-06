<template>
    <v-col cols="6">
        <span class="">{{heroClass.name}}</span>
        <HeroClassIcon :hero-class="heroClass"></HeroClassIcon>
    </v-col>
</template>

<script>
    import HeroClass from "../../../models/HeroClass";
    import HeroClassIcon from "../../icons/heroClasses/HeroClassIcon";

    export default {
        name: "RecruitmentCampHeroClassSelector",
        components: {HeroClassIcon},
        props: {
            heroClass: {
                type: HeroClass,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
