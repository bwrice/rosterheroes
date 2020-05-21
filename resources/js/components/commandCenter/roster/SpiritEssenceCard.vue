<template>
    <v-sheet color="#576269">
        <v-row no-gutters>
            <v-col cols="8" class="d-flex flex-column justify-center px-2 pb-1">
                <span class="subtitle-1 font-weight-light ma-1 underline">Essence Remaining</span>
                <v-sheet color="rgba(0,0,0, 0.5)" class="pa-2">
                    <span class="display-3 font-weight-bold" style="color: rgba(163, 255, 217, .85)">{{_availableSpiritEssence.toLocaleString()}}</span>
                </v-sheet>
            </v-col>
            <v-col cols="4">
                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 mt-2 mb-1">
                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                        <span class="overline">Total</span>
                        <span class="caption">30,000</span>
                    </v-row>
                </v-sheet>
                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 my-1">
                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                        <span class="overline">Tot./Hero</span>
                        <span class="caption">7,500</span>
                    </v-row>
                </v-sheet>
                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 mt-1 mb-2">
                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                        <span class="overline">Rem./Hero</span>
                        <span class="caption">5,391</span>
                    </v-row>
                </v-sheet>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        name: "SpiritEssenceCard",

        computed: {
            ...mapGetters([
                '_heroes',
                '_availableSpiritEssence'
            ])
        },
    }
</script>

<style scoped>

</style>
