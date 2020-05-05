<template>
    <v-row no-gutters align="center" justify="center" :class="[textSizeClass, fontWeightClass]">
        {{displayText}}
    </v-row>
</template>

<script>
    export default {
        name: "DisplayHeaderText",
        props: {
            displayText: {
                type: String,
                required: true
            }
        },
        computed: {
            textSizeClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'display-1';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'display-3'
                }
            },
            fontWeightClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'font-weight-medium';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'font-weight-bold'
                }
            }
        }
    }
</script>

<style scoped>

</style>
