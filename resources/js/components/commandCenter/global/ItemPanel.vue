<template>
    <v-row>
        <v-col cols="3"></v-col>
        <v-col cols="5">
            <p>{{item.name}}</p>
        </v-col>
        <v-col cols="4"></v-col>
    </v-row>
</template>

<script>
    export default {
        name: "ItemPanel",
        props: {
            item: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
