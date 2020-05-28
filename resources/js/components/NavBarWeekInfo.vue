<template>
    <v-menu
        bottom
        left
        v-model="opened"
    >
        <template v-slot:activator="{ on }">
            <v-btn
                :color="iconColor"
                icon
                v-on="on"
            >
                <v-icon>schedule</v-icon>
            </v-btn>
        </template>

        <v-list>
            <v-list-item>
                <v-spacer></v-spacer>
                <v-icon @click="opened = false">close</v-icon>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>
    export default {
        name: "NavBarWeekInfo",
        data() {
            return {
                opened: false
            }
        },
        computed: {
            iconColor() {
                return 'success';
            }
        }
    }
</script>

<style scoped>

</style>
