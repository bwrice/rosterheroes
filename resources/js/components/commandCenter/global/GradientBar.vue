<template>
    <div class="v-progress-linear rh-gradient-progress" :style="progressStyles">
        <div class="v-progress-linear__determinate rh-progress-missing" :style="progressMissingStyles">
        </div>
        <div class="v-progress-linear__content">
            <slot>
                <!-- default slot -->
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "GradientBar",
        props: {
            height: {
                type: Number,
                required: true
            },
            percent: {
                type: Number,
                required: true
            }
        },
        computed: {
            progressStyles() {
                return {
                    height: this.height + 'px'
                }
            },
            progressMissing() {
                return 100 - this.percent;
            },
            progressMissingStyles() {
                return {
                    left: this.percent + '%',
                    width: this.progressMissing + '%'
                }
            }
        }
    }
</script>

<style scoped>
    .rh-gradient-progress {
        /*background-image: linear-gradient(to right, #333491, #6024ad, #9c13cf, #f500f5);*/
        background-image: linear-gradient(to right, #cfc07e, #e0a06c, #ed4040, #fc00c5);
    }
    .rh-progress-missing {
        position: absolute;
        background-color: #424242
    }
</style>
