<template>
    <SvgIconSheet
        :color="'#C8DDE0'"
        class="pa-1"
    >
        <component :is="classTypeComponent"></component>
    </SvgIconSheet>
</template>

<script>
    import {mapGetters} from 'vuex';
    import SvgIconSheet from "../../commandCenter/global/SvgIconSheet";
    import WarriorIcon from "./WarriorIcon";
    import RangerIcon from "./RangerIcon";
    import SorcererIcon from "./SorcererIcon";
    import HeroClass from "../../../models/HeroClass";
    export default {
        name: "HeroClassIcon",
        components: {SvgIconSheet, WarriorIcon, RangerIcon, SorcererIcon},
        props: {
            heroClass: {
                type: HeroClass,
                required: true
            },
        },
        computed: {
            ...mapGetters([
                '_heroClassByID'
            ]),
            classTypeComponent() {
                if (this.heroClass.name === 'warrior') {
                    return 'WarriorIcon';
                }
                if (this.heroClass.name === 'ranger') {
                    return 'RangerIcon';
                }
                return 'SorcererIcon';
            }
        }
    }
</script>

<style scoped>

</style>
