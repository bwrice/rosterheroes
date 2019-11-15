<template>
    <v-card>
        <v-card-title>{{skirmish.name}}</v-card-title>
    </v-card>
</template>

<script>
    import Skirmish from "../../../models/Skirmish";

    export default {
        name: "SkirmishCard",
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
