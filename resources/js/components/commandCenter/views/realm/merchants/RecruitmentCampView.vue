<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                Recruitment Camp View
            </v-col>
            <v-col cols="12" sm="8" offset-sm="2" md="6" offset-md="0" lg="6" offset-lg="1" xl="5" offset-xl="2">
                <slot name="column-one"></slot>
            </v-col>
            <v-col cols="12" sm="8" offset-sm="2" md="6" offset-md="0" lg="4" xl="3">
                <slot name="column-two"></slot>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "RecruitmentCampView"
    }
</script>

<style scoped>

</style>
