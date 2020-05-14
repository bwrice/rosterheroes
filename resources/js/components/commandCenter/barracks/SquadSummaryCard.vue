<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">REPORT: {{_squad.name}}</span>
        </v-col>
        <v-col cols="12">
            <v-sheet
                color="#50877d"
                class="my-1"
            >
                <v-row no-gutters>
                    <v-col cols="4">
                        <v-row no-gutters class="pa-2">
                            <v-col cols="12">
                                <LevelIcon :level="_squad.level"></LevelIcon>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="8">
                        <v-row no-gutters class="pa-3">
                            <v-col cols="12">
                                <v-row no-gutters>
                                    <span class="font-weight-regular" :class="[textSizeClass]">Gold: {{_squad.gold.toLocaleString()}}</span>
                                </v-row>
                                <v-row no-gutters>
                                    <span class="font-weight-regular" :class="[textSizeClass]">Experience: {{_squad.experience.toLocaleString()}}</span>
                                </v-row>
                                <v-row no-gutters>
                                    <span class="font-weight-regular" :class="[textSizeClass]">Spirit Essence: {{_squad.spiritEssence.toLocaleString()}}</span>
                                </v-row>
                                <v-row no-gutters>
                                    <span class="font-weight-regular" :class="[textSizeClass]">Location: {{_currentLocationProvince.name}}</span>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import LevelIcon from "../../icons/LevelIcon";
    export default {
        name: "SquadSummaryCard",
        components: {LevelIcon},
        computed: {
            ...mapGetters([
                '_squad',
                '_currentLocationProvince'
            ]),
            textSizeClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'sub-title';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'title'
                }
            },
        }
    }
</script>

<style scoped>

</style>
