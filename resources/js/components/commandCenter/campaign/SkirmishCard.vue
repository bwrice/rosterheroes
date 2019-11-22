<template>
    <v-sheet color="#5c707d" class="pa-2">
        <v-row no-gutters class="py-2" justify="space-between" align="center">
            <span class="title rh-op-85 font-weight-regular">
                {{skirmish.name}}
            </span>
            <v-chip
                label
                color="rgba(0,0,0,.25)"
            >
                {{skirmish.difficulty}}
            </v-chip>
        </v-row>
        <v-row no-gutters>
            <v-carousel
                :height="height"
                hide-delimiter-background
                show-arrows-on-hover
            >
                <v-carousel-item
                    v-for="(minion, uuid) in skirmish.minions"
                    :key="uuid"
                >
                    <MinionPanel :minion="minion" :height="height"></MinionPanel>
                </v-carousel-item>
            </v-carousel>
        </v-row>
        <v-row no-gutters justify="end">
            <v-btn
                v-if="hasSkirmish"
                color="error"
                class="mt-2"
            >
                Remove Skirmish
            </v-btn>
            <v-btn
                v-else
                color="primary"
                class="mt-2"
                :disabled="! canAddSkirmish"
                @click="addSkirmish"
            >
                Add Skirmish
            </v-btn>
        </v-row>
    </v-sheet>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import Skirmish from "../../../models/Skirmish";
    import MinionPanel from "../views/campaign/MinionPanel";
    import Quest from "../../../models/Quest";

    export default {
        name: "SkirmishCard",
        components: {MinionPanel},
        props: {
            skirmish: {
                type: Skirmish,
                required: true
            },
            quest: {
                type: Quest,
                required: true
            },
            height: {
                type: Number,
                default: 300
            }
        },
        data() {
            return {
                pending: false
            }
        },
        computed: {
            ...mapGetters([
                '_matchingCampaignStop',
                '_squadSkirmishUuids'
            ]),
            canAddSkirmish() {
                return (this.campaignStop !== undefined && ! this.hasSkirmish && ! this.pending);
            },
            campaignStop() {
                return this._matchingCampaignStop(this.quest.uuid);
            },
            hasSkirmish() {
                let localSkirmish = this.skirmish;
                let matchingSkirmish = this._squadSkirmishUuids.find(uuid => uuid === localSkirmish.uuid);
                return matchingSkirmish !== undefined;
            }
        },
        methods: {
            ...mapActions([
                'addSkirmishToCampaignStop'
            ]),
            async addSkirmish() {
                this.pending = true;
                await this.addSkirmishToCampaignStop({
                    campaignStop: this.campaignStop,
                    skirmish: this.skirmish
                });
                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>
