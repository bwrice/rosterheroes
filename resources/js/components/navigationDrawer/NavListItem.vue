<template>
    <v-list-item :to="to" color="accent">
        <span class="font-weight-light">{{title}}</span>
    </v-list-item>
</template>

<script>
    export default {
        name: "NavListItem",
        props: {
            to: {
                required: true
            },
            title: {
                required: true,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
