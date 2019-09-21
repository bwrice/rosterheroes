<template>
    <v-card color="rgba(255, 255, 255, 0.1)">
        <v-card-text>
            <v-row>
                <h3>{{item.name}}</h3>
            </v-row>
            <v-row class="py-0 my-0">
                <v-col cols="6">
                    <p class="caption">
                        Type: {{item.itemType.name}} <br>
                        Class: {{item.itemClass.name}} <br>
                        Material: {{item.material.name}} <br>
                    </p>
                </v-col>
                <v-col cols="6">
                    <p class="caption">
                        Protection: {{item.protection}} <br>
                        Block %: {{item.blockChance}} <br>
                        Burden: {{item.burden}} <br>
                        Value: {{item.value}} <br>
                    </p>
                </v-col>
            </v-row>
            <v-row>
                <h4>Attacks:</h4>
                <AttackPanel v-for="attack in item.attacks" v-bind:key="attack.name" :attack="attack"></AttackPanel>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
    import Item from "../../../models/Item";
    import AttackPanel from "./AttackPanel";

    export default {
        name: "ItemCard",
        components: {AttackPanel},
        props: {
            item: {
                type: Item,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
