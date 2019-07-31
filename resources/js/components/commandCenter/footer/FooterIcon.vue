<template>
    <v-btn
            color="#ffc747"
            text
            :value="value"
            :to="to"
            style="min-width: 52px"
    >
        <v-sheet
                class="d-flex pa-1"
                color="transparent"
                height="60"
                width="60"
        >
            <slot></slot>
        </v-sheet>
    </v-btn>
</template>

<script>
    export default {
        name: "FooterIcon",
        props: ['value', 'to']
    }
</script>

<style scoped>

</style>