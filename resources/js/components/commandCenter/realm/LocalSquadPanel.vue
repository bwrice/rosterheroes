<template>
    <v-sheet color="#536a80"
             style="margin: 2px 0 2px 0"
             class="rounded"
    >
        <v-row no-gutters align="center" justify="center" class="mx-2">
            <div style="flex: 0 0 15%" class="pt-2">
                <LevelIcon :level="localSquad.level"></LevelIcon>
            </div>
            <div class="flex-grow-1"></div>
            <span class="headline font-weight-light">{{localSquad.name}}</span>
            <div class="flex-grow-1"></div>
            <v-btn @click="expanded = ! expanded"
                   fab
                   small
                   color="rgba(0, 0, 0, .4)"
                   class="mx-2"
            >
                <v-icon v-if="expanded">expand_less</v-icon>
                <v-icon v-else>expand_more</v-icon>
            </v-btn>
        </v-row>
        <v-row no-gutters v-if="expanded">
            <v-col cols="12"
                   v-for="(localHero, uuid) in localSquad.localHeroes"
                   :key="uuid"
            >
                <LocalHeroPanel :local-hero="localHero"></LocalHeroPanel>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import LocalSquad from "../../../models/LocalSquad";
    import LevelIcon from "../../icons/LevelIcon";
    import LocalHeroPanel from "./LocalHeroPanel";

    export default {
        name: "LocalSquadPanel",
        components: {LocalHeroPanel, LevelIcon},
        props: {
            localSquad: {
                type: LocalSquad,
                required: true
            }
        },
        data() {
            return {
                expanded: false
            }
        }
    }
</script>

<style scoped>

</style>
