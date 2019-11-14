<template>
    <ClickableSheet
        :color="'#5c707d'"
        :hovered-color="'#6f808c'"
        :classes-object="{'ma-1': true}"
    >
        <v-row no-gutters>
            <v-col cols="12">
                <span class="subtitle-1 font-weight-light">{{quest.name}}</span>
            </v-col>
        </v-row>
    </ClickableSheet>
</template>

<script>
    import Quest from "../../../models/Quest";
    import ClickableSheet from "../../shared/ClickableSheet";

    export default {
        name: "QuestSummaryPanel",
        components: {ClickableSheet},
        props: {
            quest: {
                type: Quest,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
