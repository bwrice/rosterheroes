<template>
    <v-list-item :key="combatEventMessage.eventUuid" style="min-height: 64px">
        <v-list-item-action>
            <v-btn
                fab
                x-small
                depressed
                outlined
                :color="fabColor"
            >
                {{ combatEventMessage.moment }}
            </v-btn>
        </v-list-item-action>

        <v-list-item-content>
            <span class="text-caption">{{combatEventMessage.message}}</span>
        </v-list-item-content>
    </v-list-item>
</template>

<script>
    import CombatEventMessage from "../../../models/CombatEventMessage";

    export default {
        name: "CombatEventMessage",
        props: {
            combatEventMessage: {
                type: CombatEventMessage,
                required: true
            }
        },
        computed: {
            fabColor() {
                return this.combatEventMessage.allySide ? 'primary' : 'accent';
            }
        }
    }
</script>

<style scoped>

</style>
