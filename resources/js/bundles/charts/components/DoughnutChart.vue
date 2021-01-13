<script>
import { Doughnut, mixins } from "vue-chartjs";
const { reactiveProp } = mixins;

export default {
    extends: Doughnut,
    props: ["options", "chartData"],
	mixins: [reactiveProp],
    mounted() {
        this.renderChart(this.chartData, this.options)
    },
	updated() {
		this.renderChart(this.chartData, this.options)
	}
}
</script>
