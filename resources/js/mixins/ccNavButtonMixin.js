import FooterIcon from '../components/commandCenter/footer/FooterIcon';

export const navButtonMixin = {

    props: ['value','activeNavButton'],
    components: {
        FooterIcon
    },
    computed: {
        outlineColor: function() {
            return this.isActive ? '#ffffff' : '#ffc747';
        },
        fillColor: function() {
            return this.isActive ? '#ffc747' : 'none';
        },
        isActive: function() {
            return this.name === this.activeNavButton;
        },
        to: function() {
            return '/cc/' + this.$route.params.squadSlug + '/' + this.name;
        }
    }
};