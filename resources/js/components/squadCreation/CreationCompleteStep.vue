<template>
    <SquadCreationStep :step="6">
        <v-row no-gutters class="flex-column" align="center" justify="center" :style="mainRowStyles">
            <v-icon
                color="success"
                :style="checkMarkStyles"
                class="mb-6"
            >check</v-icon>
            <span :class="textClasses" style="color: rgba(255, 255, 255, .85)">
                Congrats!!!, Your squad,
            </span>
            <v-sheet color="rgba(255, 255, 255, 0.15)" :class="squadSheetClasses" class="rounded">
                <span class="font-weight-bold" :class="squadNameClasses">
                    {{ squad.name }}
                </span>
            </v-sheet>
            <span class="px-6 text-center" :class="textClasses" style="color: rgba(255, 255, 255, .85)">
                is all set up. You can now head over to the
                command center to begin your journey.
            </span>
            <v-btn
                :href="'/command-center/' + this.squad.slug"
                color="primary"
                class="my-6"
                :x-large="! isMobile"
            >
                Command Center
            </v-btn>
        </v-row>
    </SquadCreationStep>
</template>

<script>
    import SquadCreationStep from "./SquadCreationStep";
    export default {
        name: "CreationCompleteStep",
        components: {SquadCreationStep},
        props: {
            squad: {
                type: Object,
                default: function () {
                    return {}
                }
            },
        },

        computed: {
            checkMarkStyles() {
                return {
                    'font-size': this.checkMarkHeight + 'px'
                }
            },
            checkMarkHeight() {
                return this.isMobile ? 160 : 240;
            },
            squadNameClasses() {
                if (this.isMobile) {
                    return {
                        'title': true,
                    }
                }
                return {
                    'display-2': true,
                }
            },
            squadSheetClasses() {
                if (this.isMobile) {
                    return {
                        'px-3': true,
                        'py-1': true,
                        'my-2': true,
                    }
                }
                return {
                    'px-4': true,
                    'py-3': true,
                    'my-4': true,
                }
            },
            isMobile() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return true;
                }
                return false;
            },
            mainRowStyles() {
                let padding = this.isMobile ? 160 : 360;
                return {
                    'padding-bottom' : padding + 'px'
                }
            },
            textClasses() {
                if (this.isMobile) {
                    return {
                        'body-1': true,
                    }
                }
                return {
                    'headline': true,
                }
            }

        }
    }
</script>

<style scoped>

</style>
