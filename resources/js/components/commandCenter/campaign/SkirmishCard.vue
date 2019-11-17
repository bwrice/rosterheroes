<template>
    <v-sheet color="#5c707d" class="pa-2">
        <v-row no-gutters class="py-2" justify="space-between" align="center">
            <span class="title rh-op-85 font-weight-regular">
                {{skirmish.name}}
            </span>
            <v-chip
                label
                color="rgba(0,0,0,.25)"
            >
                {{skirmish.difficulty}}
            </v-chip>
        </v-row>
        <v-row no-gutters>
            <v-carousel
                :height="height"
                hide-delimiter-background
                show-arrows-on-hover
            >
                <v-carousel-item
                    v-for="(minion, uuid) in skirmish.minions"
                    :key="uuid"
                >
                    <MinionPanel :minion="minion" :height="height"></MinionPanel>
                </v-carousel-item>
            </v-carousel>
        </v-row>
    </v-sheet>
</template>

<script>
    import Skirmish from "../../../models/Skirmish";
    import MinionPanel from "../views/campaign/MinionPanel";

    export default {
        name: "SkirmishCard",
        components: {MinionPanel},
        props: {
            skirmish: {
                type: Skirmish,
                required: true
            },
            height: {
                type: Number,
                default: 300
            }
        }
    }
</script>

<style scoped>

</style>
