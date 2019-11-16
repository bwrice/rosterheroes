<template>
    <v-card>
        <v-card-title class="rh-op-85">{{skirmish.name}}</v-card-title>
        <v-card-text>
            <v-carousel
                height="200"
            >
                <v-carousel-item
                    v-for="(minion, uuid) in skirmish.minions"
                    :key="uuid"
                >
                    <MinionPanel :minion="minion"></MinionPanel>
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
            }
        }
    }
</script>

<style scoped>

</style>
