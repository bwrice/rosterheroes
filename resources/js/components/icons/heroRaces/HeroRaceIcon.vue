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
    import HeroRace from "../../../models/HeroRace";
    import HumanIcon from "./HumanIcon";
    import ElfIcon from "./ElfIcon";
    import DwarfIcon from "./DwarfIcon";
    import OrcIcon from "./OrcIcon";
    export default {
        name: "HeroRaceIcon",
        components: {SvgIconSheet, HumanIcon, ElfIcon, DwarfIcon, OrcIcon},
        props: {
            heroRace: {
                type: HeroRace,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_heroClassByID'
            ]),
            classTypeComponent() {
                if (this.heroRace.name === 'human') {
                    return 'HumanIcon';
                }
                if (this.heroRace.name === 'elf') {
                    return 'ElfIcon';
                }
                if (this.heroRace.name === 'dwarf') {
                    return 'DwarfIcon';
                }
                return 'OrcIcon';
            }
        }
    }
</script>

<style scoped>

</style>
