<template>
	<div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-coins"></i>
                Means of Payment
            </div>
        </div>

		<div class="card-body">
			<div>
                <div v-if="content[currency].length" class="table-responsive">
                    <table class="table-themed responsive-table-hover">
                        <TableHeader
                            :cells="header"
                        ></TableHeader>

                        <tbody>
                            <MeansOfPaymentTableRow
                                v-for="(item, i) in content[currency]"
                                :key="i"
                                v-model="content[currency][i]"
                                :index="i"
                                @delete="remove(i)"
                            >
                            </MeansOfPaymentTableRow>
                        </tbody>
                    </table>
                </div>

                <EmptyPlaceholder v-else></EmptyPlaceholder>
			</div>

            <hr class="hr">

            <div class="row">
                <div class="col-md-4">
                    <button
                        class="big-button-primary"
                        :disabled="submitted"
                        @click="create"
                    >
                        New mean of payment
                    </button>
                </div>

                <div class="col-md-4 my-2 my-md-0">
                    <button
                        class="big-button-danger"
                        :disabled="submitted"
                        @click="reset"
                    >
                        Reset changes
                    </button>
                </div>

                <div class="col-md-4">
                    <button
                        :class="[
                            'big-button-success',
                            !equalContent && !submitted && 'text-stand-out'
                        ]"
                        :disabled="!canSave || submitted"
                        @click="submit"
                    >
                        <div v-if="!submitted">
                            Save changes
                        </div>

                        <span
                            v-else
                            class="spinner-border spinner-border-sm"
                            role="status"
                            aria-hidden="true"
                        ></span>
                    </button>
                </div>
            </div>
		</div>
	</div>
</template>

<script>
import TableHeader from "../TableHeader.vue";
import EmptyPlaceholder from  "../EmptyPlaceholder.vue";
import MeansOfPaymentTableRow from "./MeansOfPaymentTableRow.vue";
export default {
    props: {
        means: {
            required: true,
            type: Object
        },
        currency: {
            required: true,
            type: Number
        }
    },
    components: {
        TableHeader,
        EmptyPlaceholder,
        MeansOfPaymentTableRow
    },
    data() {
        return {
            header: [
                {
                    text: "Name",
                    tooltip: "The name of your mean of payment"
                },
                {
                    text: "Income",
                    tooltip: "Use in income"
                },
                {
                    text: "Outcome",
                    tooltip: "Use in outcome"
                },
                {
                    text: "Summary",
                    tooltip: "Count to summary"
                },
                {
                    text: "First entry",
                    tooltip: "Date from which to add the starting balance"
                },
                {
                    text: "Starting balance",
                    tooltip: "Amount which to use for the first entry"
                },
                {}
            ],
            content: {},
            contentCopy: {},

            submitted: false
        }
    },
    computed: {
        equalContent() {
            return _.isEqual(this.content, this.contentCopy);
        },
        canSave() {
            let validArray = [];

            for (let i in this.content) {
                validArray = validArray.concat(
                    this.content[i].map(item => {
                        const validName = item.name && item.name.length <= 32;

                        const validDate = item.first_entry_date && (
                            new Date(item.first_entry_date).getTime() <= new Date(item.date_limit).getTime() ||
                            item.date_limit === null);

                        const validAmount = item.first_entry_amount !== "" &&
                            Math.abs(Number(item.first_entry_amount)) <= 1e11 - 0.01;

                        return validName && validDate && validAmount;
                    })
                );
            }

            return validArray.length ?
                !!validArray.reduce((item1, item2) => item1 && item2) :
                true;
        }
    },
    methods: {
        create() {
            this.content[this.currency].push({
                id: 0,
                currency_id: this.currency,
                income_mean: false,
                outcome_mean: false,
                count_to_summary: false,
                first_entry_date: "",
                first_entry_amount: 0,
                date_limit: null
            });
        },
        remove(index) {
            this.content[this.currency].splice(index, 1);
        },
        reset() {
            this.content = _.cloneDeep(this.contentCopy);
        },
        submit() {
            this.submitted = true;

            axios
                .post("/webapi/settings/means", {
                    data: this.content
                })
                .then(response => {
                    const data = response.data;
                    this.content = data.data;
                    this.contentCopy = _.cloneDeep(data.data);

                    this.submitted = false;
                })
                .catch(err => {
                    console.log(err);
                    this.submitted = false;
                })
        },
        refreshTooltip() {
            this.$nextTick(() => {
                updateTooltips();
            });
        }
    },
    beforeMount() {
        this.content = _.cloneDeep(this.means);
        this.contentCopy = _.cloneDeep(this.means);
    },
    mounted() {
        this.refreshTooltip();
    },
    updated() {
        this.refreshTooltip();
    }
}
</script>
