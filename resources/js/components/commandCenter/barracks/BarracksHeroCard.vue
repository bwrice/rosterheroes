<template>
    <v-card>
        <v-card-title>{{hero.name}}</v-card-title>
        <v-card-actions>
            <v-btn>Edit</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "BarracksHeroCard",
        props: {
            hero: {
                type: Object,
                require: true
            }
        }
    }
</script>

<style scoped>

</style>
