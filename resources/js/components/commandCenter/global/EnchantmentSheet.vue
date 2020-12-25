<template>
    <v-sheet color="rgb(83, 83, 115)" class="px-2 my-1 rounded-sm">
        <v-row>
            <v-col cols="12">
                <span class="subtitle-2">
                    {{enchantment.name}}:
                </span>
                <ul>
                    <li v-for="boost in enchantment.measurableBoosts">
                        {{boost.description}}
                    </li>
                </ul>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import Enchantment from "../../../models/Enchantment";

    export default {
        name: "EnchantmentSheet",
        props: {
            enchantment: {
                type: Enchantment,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
