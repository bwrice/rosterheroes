<template>
    <div class="v-progress-linear" :style="progressStyles">
        <div class="v-progress-linear__determinate" :style="progressMissingStyles">
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
            },
            progressGradientOne: {
                type: String,
                default: '#cfc07e'
            },
            progressGradientTwo: {
                type: String,
                default: '#e0a06c'
            },
            progressGradientThree: {
                type: String,
                default: '#ed4040'
            },
            progressGradientFour: {
                type: String,
                default: '#fc00c5'
            },
            progressMissingBackground: {
                type: String,
                default: '#424242'
            }
        },
        computed: {
            progressStyles() {
                let backGroundImage = 'linear-gradient(to right, ';
                backGroundImage += this.progressGradientOne + ', ';
                backGroundImage += this.progressGradientTwo + ', ';
                backGroundImage += this.progressGradientThree + ', ';
                backGroundImage += this.progressGradientFour + ')';
                return {
                    height: this.height + 'px',
                    'background-image': backGroundImage
                }
            },
            progressMissing() {
                return 100 - this.percent;
            },
            progressMissingStyles() {

                return {
                    left: this.percent + '%',
                    width: this.progressMissing + '%',
                    position: 'absolute',
                    'background-color': this.progressMissingBackground
                }
            }
        }
    }
</script>

<style scoped>

</style>
