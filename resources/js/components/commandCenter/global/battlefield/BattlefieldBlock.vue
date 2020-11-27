<template>
    <g :transform="transform">
        <path d="M12 4.942c1.827 1.105 3.474 1.6 5 1.833v7.76c0 1.606-.415 1.935-5 4.76v-14.353zm9-1.942v11.535c0
        4.603-3.203 5.804-9 9.465-5.797-3.661-9-4.862-9-9.465v-11.535c3.516 0 5.629-.134 9-3 3.371 2.866 5.484 3 9
        3zm-2 1.96c-2.446-.124-4.5-.611-7-2.416-2.5 1.805-4.554 2.292-7 2.416v9.575c0 3.042 1.69 3.83 7 7.107
        5.313-3.281 7-4.065 7-7.107v-9.575z">
        </path>
    </g>
</template>

<script>
    import TWEEN from '@tweenjs/tween.js';

    export default {
        name: "BattlefieldBlock",
        props: {
            battlefieldBlock: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                scale: 0,
                xPosition: 0,
                yPosition: 0
            }
        },
        created() {
            this.setAndTweenScale(this.battlefieldBlock);
        },
        watch: {
            battlefieldBlock(newValue) {
                this.setAndTweenScale(newValue);
            }
        },
        methods: {
            setAndTweenScale(battlefieldBlock) {
                this.scale = 2;
                let originalXPosition = battlefieldBlock.xPosition;
                this.xPosition = originalXPosition;
                let originalYPosition = battlefieldBlock.yPosition;
                this.yPosition = originalYPosition;
                tweenScale(this.$data, originalXPosition, originalYPosition);
            }
        },
        computed: {
            transform() {
                return 'translate('+ this.xPosition + ',' + this.yPosition + ') scale(' + this.scale + ')';
            }
        }
    }

    function tweenScale(data, originalXPosition, originalYPosition) {
        function animate () {
            if (TWEEN.update()) {
                requestAnimationFrame(animate)
            }
        }
        new TWEEN.Tween(data)
            .to({
                scale: 4,
                xPosition: originalXPosition - 20,
                yPosition: originalYPosition - 20
            }, 500)
            .easing(TWEEN.Easing.Bounce.Out)
            .start();

        animate();
    }
</script>

<style scoped>

</style>
