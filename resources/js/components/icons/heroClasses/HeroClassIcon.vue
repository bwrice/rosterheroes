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
    export default {
        name: "HeroClassIcon",
        components: {SvgIconSheet, WarriorIcon, RangerIcon, SorcererIcon},
        props: {
            heroClassId: {
                type: Number,
                required: true
            },
        },
        computed: {
            ...mapGetters([
                '_heroClassByID'
            ]),
            heroClass() {
                return this._heroClassByID(this.heroClassId);
            },
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
