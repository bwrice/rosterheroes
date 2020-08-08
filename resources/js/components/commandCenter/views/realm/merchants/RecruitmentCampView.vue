<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-row no-gutters justify="center" class="py-md-2">
                    <span class="rh-op-85 text-center text-h4 text-h2-md">{{_recruitmentCamp.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" md="5" offset-md="1" lg="4" offset-lg="2" xl="3" offset-xl="3">
                <v-row no-gutters>
                    <v-col cols="12">
                        <span class="title font-weight-thin">HERO RACE</span>
                    </v-col>
                </v-row>
                <HeroPostTypeSelectorPanel
                    v-for="(heroPostType, id) in _recruitmentCamp.heroPostTypes"
                    :key="id"
                    :hero-post-type="heroPostType"
                ></HeroPostTypeSelectorPanel>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" md="5" offset-md="0" lg="4" xl="3">
                <v-row no-gutters>
                    <v-col cols="12">
                        <span class="title font-weight-thin">HERO CLASS</span>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="4" v-for="(heroClass, id) in heroClasses" :key="id" class="px-1">
                        <RecruitmentCampHeroClassSelector
                            :hero-class="heroClass"
                        ></RecruitmentCampHeroClassSelector>
                    </v-col>
                </v-row>
                <v-row no-gutters class="pt-4">
                    <v-col cols="12">
                        <span class="title font-weight-thin">HERO</span>
                    </v-col>
                </v-row>
                <v-row no-gutters justify="center">
                    <div v-if="_recruitmentHeroRace" :style="heroIconStyles">
                        <HeroRaceIcon :hero-race="_recruitmentHeroRace"></HeroRaceIcon>
                    </div>
                    <div v-else class="empty-hero-type" :style="emptyHeroStyles"></div>
                    <div v-if="_recruitmentHeroClass" :style="heroIconStyles">
                        <HeroClassIcon :hero-class="_recruitmentHeroClass"></HeroClassIcon>
                    </div>
                    <div v-else class="empty-hero-type" :style="emptyHeroStyles"></div>
                    <v-text-field
                        label="Hero Name"
                        name="hero-name"
                        outlined
                        v-model="name"
                        @blur="$v.name.$touch()"
                        :error-messages="nameErrors"
                        messages="Letters, numbers and spaces allowed"
                    ></v-text-field>
                </v-row>
                <v-row no-gutters justify="center" class="pt-6">
                    <v-btn
                        color="primary"
                        x-large
                        :disabled="disabled"
                        @click="recruitDialog = true"
                    >
                        Recruit Hero
                    </v-btn>
                </v-row>
            </v-col>
        </v-row>
        <v-dialog
            v-model="recruitDialog"
            max-width="500"
        >
            <v-sheet>
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row no-gutters justify="center">
                            <v-col cols="6">
                                <span class="text-body-1 font-weight-light">RACE: </span>
                                <span class="text-body-1 font-weight-bolder">{{_recruitmentHeroRace ? _recruitmentHeroRace.name.toUpperCase() : ''}}</span>
                            </v-col>
                            <v-col cols="6">
                                <span class="text-body-1 font-weight-light">CLASS: </span>
                                <span class="text-body-1 font-weight-bolder">{{_recruitmentHeroClass ? _recruitmentHeroClass.name.toUpperCase() : ''}}</span>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row no-gutters justify="center" class="pa-2">
                    <v-btn
                        outlined
                        color="error"
                        @click="recruitDialog = false"
                        class="mx-1"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        color="success"
                        class="mx-1"
                    >
                        Recruit Hero
                    </v-btn>
                </v-row>
            </v-sheet>
        </v-dialog>
    </v-container>
</template>

<script>

    import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators'
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import RecruitmentCampHeroClassSelector from "../../../realm/RecruitmentCampHeroClassSelector";
    import HeroPostTypeSelectorPanel from "../../../realm/HeroPostTypeSelectorPanel";
    import HeroRaceIcon from "../../../../icons/heroRaces/HeroRaceIcon";
    import HeroClassIcon from "../../../../icons/heroClasses/HeroClassIcon";

    export default {
        name: "RecruitmentCampView",
        components: {
            HeroClassIcon,
            HeroRaceIcon,
            HeroPostTypeSelectorPanel,
            RecruitmentCampHeroClassSelector
        },
        mounted() {
            this.maybeUpdateRecruitmentCamp();
        },
        validations: {
            name: {
                required,
                minLength: minLength(4),
                maxLength: maxLength(16),
                format: helpers.regex('format', /^[\w\s]+$/)
            }
        },
        data () {
            return {
                name: '',
                recruitDialog: false
            }
        },
        methods: {
            ...mapActions([
                'updateRecruitmentCamp',
                'recruitHero'
            ]),
            maybeUpdateRecruitmentCamp() {
                let recruitmentCampSlug = this.$route.params.recruitmentCampSlug;
                if (this._recruitmentCamp.slug !== recruitmentCampSlug) {
                    this.updateRecruitmentCamp(this.$route);
                }
            },
            handleClick() {
                this.recruitHero({
                    route: this.$route,
                    heroName: this.name
                });
            }
        },
        computed: {
            ...mapGetters([
                '_recruitmentCamp',
                '_heroClassByID',
                '_recruitmentHeroRace',
                '_recruitmentHeroClass',
                '_recruitmentHeroPostType'
            ]),
            heroClasses() {
                return this._recruitmentCamp.heroClassIDs.map(heroClassID => this._heroClassByID(heroClassID));
            },
            emptyHeroStyles() {
                if (this.$vuetify.breakpoint.name === 'xs') {
                    return {
                        height: '53px',
                        width: '44px'
                    }
                }
                return {
                    height: '80px',
                    width: '66px'
                }
            },
            heroIconStyles() {
                if (this.$vuetify.breakpoint.name === 'xs') {
                    return {
                        height: '60px',
                        width: '48px'
                    }
                }
                return {
                    height: '88px',
                    width: '70px'
                }
            },
            disabled() {
                if (! this._recruitmentHeroRace) {
                    return true;
                }
                if (! this._recruitmentHeroClass) {
                    return true;
                }
                if (! this.name) {
                    return true
                }
                return this.nameErrors.length > 0;
            },
            nameErrors() {
                const errors = [];
                if (!this.$v.name.$dirty) return errors;
                !this.$v.name.required && errors.push('Name is required');
                !this.$v.name.minLength && errors.push('Must be at least 4 characters');
                !this.$v.name.maxLength && errors.push('Cannot be more than 16 characters');
                !this.$v.name.format && errors.push('Only letters, number and spaces allowed');
                // if (this.serverErrors.has('name')) {
                //     this.serverErrors.get('name').forEach(function(error) {
                //         errors.push(error);
                //     })
                // }
                return errors
            },
        }
    }
</script>

<style scoped>
    .empty-hero-type {
        border-radius: 4px;
        border-style: dotted;
        border-color: rgba(168, 168, 168, 0.8);
        border-width: thin;
        margin: 2px;
    }
</style>
