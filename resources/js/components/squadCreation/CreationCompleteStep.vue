<template>
    <SquadCreationStep :step="6">
        <v-row no-gutters class="flex-column" align="center" justify="center" style="padding-bottom: 160px">
            <v-icon
                color="success"
                style="font-size: 160px"
                class="mb-6"
            >check</v-icon>
            <span class="body-1" style="color: rgba(255, 255, 255, .85)">
                Congrats!!!, Your squad,
            </span>
            <v-sheet color="rgba(255, 255, 255, 0.15)" class="px-3 py-1 my-2">
                <span class="title font-weight-bold">
                    {{ squadClone.name }}
                </span>
            </v-sheet>
            <span class="body-1 px-4 text-center" style="color: rgba(255, 255, 255, .85)">
                is all set up. You can now head over to the
                command center to begin your journey.
            </span>
            <v-btn
                :href="'/command-center/' + this.squadClone.slug"
                color="primary"
                class="my-6"
            >
                Command Center
            </v-btn>
        </v-row>
    </SquadCreationStep>
</template>

<script>
    import SquadCreationStep from "./SquadCreationStep";
    export default {
        name: "CreationCompleteStep",
        components: {SquadCreationStep},
        props: {
            squad: {
                type: Object,
                default: function () {
                    return {}
                }
            },
        },

        created() {
            this.squadClone = _.cloneDeep(this.squad);
        },
    }
</script>

<style scoped>

</style>
