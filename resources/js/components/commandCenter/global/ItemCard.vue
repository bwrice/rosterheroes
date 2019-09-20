<template>
    <v-card>
        <v-card-text>
            <v-row>
                <h4>{{item.name}}</h4>
            </v-row>
            <v-row>
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
        </v-card-text>
    </v-card>
</template>

<script>
    import Item from "../../../models/Item";

    export default {
        name: "ItemCard",
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
