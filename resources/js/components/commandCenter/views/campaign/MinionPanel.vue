<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-row no-gutters>
                <span class="subtitle-2">
                    {{minion.name}}
                </span>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import Minion from "../../../../models/Minion";

    export default {
        name: "MinionPanel",
        props: {
            minion: {
                type: Minion,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
