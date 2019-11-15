<template>
    <ClickableSheet
        :classes-object="{'ma-1': true}"
        @click="routeToQuestView"
    >
        <v-row no-gutters>
            <v-col cols="12">
                <v-row no-gutters justify="space-between" align="center" class="pa-1">
                    <span class="mx-3 title font-weight-light">{{quest.name}}</span>
                    <v-chip>{{quest.level}}</v-chip>
                </v-row>
                <v-row no-gutters class="pa-2">
                    <v-col cols="8" md="8">
                        <GradientBar
                            :percent="quest.percentComplete"
                            :height="16"
                        >
                            <span class="font-weight-bold" style="opacity: .7">{{quest.percentComplete}}% complete</span>
                        </GradientBar>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </ClickableSheet>
</template>

<script>
    import Quest from "../../../models/Quest";
    import ClickableSheet from "../../shared/ClickableSheet";
    import GradientBar from "../global/GradientBar";

    export default {
        name: "QuestSummaryPanel",
        components: {GradientBar, ClickableSheet},
        props: {
            quest: {
                type: Quest,
                required: true
            }
        },
        methods: {
            routeToQuestView() {
                let squadSlug = this.$route.params.squadSlug;
                let questSlug = this.quest.slug;
                this.$router.push({
                    name: 'campaign-quest',
                    params: {
                        squadSlug,
                        questSlug
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
