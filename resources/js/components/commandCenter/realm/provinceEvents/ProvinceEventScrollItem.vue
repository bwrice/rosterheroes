<template>
    <div>
        <div class="mx-2">
            <span class="font-weight-thin caption ml-1 mb-1" style="float: right">{{ago}}</span>
            <div class="d-flex align-center" style="height: 44px">
                <div class="subtitle-2 font-weight-light">
                    <slot>
                        <!-- Default Slot -->
                    </slot>
                </div>
            </div>
        </div>
        <v-divider></v-divider>
    </div>
</template>

<script>
    import ProvinceEvent from "../../../../models/ProvinceEvent";

    export default {
        name: "ProvinceEventScrollItem",
        props: {
            provinceEvent: {
                type: ProvinceEvent,
                required: true
            }
        },
        computed: {
            ago() {
                return window.moment(this.provinceEvent.happenedAt).fromNow();
            }
        }
    }
</script>

<style scoped>

</style>
