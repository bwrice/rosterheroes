<template>
    <v-card color="#5c707d">
        <v-card-title class="rh-op-85 font-weight-regular">{{skirmish.name}}</v-card-title>
        <v-card-text>
            <v-carousel
                :height="height"
                hide-delimiter-background
            >
                <v-carousel-item
                    v-for="(minion, uuid) in skirmish.minions"
                    :key="uuid"
                >
                    <MinionPanel :minion="minion" :height="height"></MinionPanel>
                </v-carousel-item>
            </v-carousel>
        </v-card-text>
    </v-card>
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
