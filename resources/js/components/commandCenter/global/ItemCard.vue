<template>
    <v-row>
        <v-col cols="3"></v-col>
        <v-col cols="6">
            <p>{{item.name}}</p>
        </v-col>
        <v-col cols="4"></v-col>
    </v-row>
</template>

<script>
    export default {
        name: "ItemCard",
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
