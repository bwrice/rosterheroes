<template>
    <v-list dense>
        <v-list-item @click="">
            <v-list-item-action>
                <v-icon>home</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title>Nation</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="">
            <v-list-item-action>
                <v-icon>contact_mail</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title>Contact</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
    </v-list>
</template>

<script>
    export default {
        name: "NationNavigationDrawer"
    }
</script>

<style scoped>

</style>