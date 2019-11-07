<template>
    <v-btn-toggle v-model="toggle_none">
        <v-btn>
            <v-icon>mdi-format-align-left</v-icon>
        </v-btn>

        <v-btn>
            <v-icon>mdi-format-align-center</v-icon>
        </v-btn>
        <v-btn>

            <v-icon>mdi-format-align-right</v-icon>
        </v-btn>

        <v-btn>
            <v-icon>mdi-format-align-justify</v-icon>
        </v-btn>
    </v-btn-toggle>
</template>

<script>
    export default {
        name: "MapArrowButtonGroup"
    }
</script>

<style scoped>

</style>
