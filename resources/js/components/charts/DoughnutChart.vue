<script>
import { Doughnut, mixins } from "vue-chartjs";
const { reactiveProp } = mixins;
import main from "&/mixins/main";

export default {
    extends: Doughnut,
    props: {
        options: {
            required: true,
            type: Object
        },
        chartData: {
            required: true,
            type: Object
        },
        theme: {
            required: true,
            type: Object
        }
    },
	mixins: [reactiveProp, main],
    computed: {
        themedOptions() {
            let result = _.cloneDeep(this.options);

            Object.keys(this.theme).forEach(item => {
                result = this.changeJsonValue(
                    result, item,
                    this.theme[item][Number(this.$vuetify.theme.dark)]
                );
            });

            return result;
        }
    },
    watch: {
        "$vuetify.theme.dark"() {
            this.$forceUpdate();
        }
    },
    mounted() {
        this.renderChart(this.chartData, this.themedOptions);
    },
	updated() {
		this.renderChart(this.chartData, this.themedOptions);
	}
}
</script>
