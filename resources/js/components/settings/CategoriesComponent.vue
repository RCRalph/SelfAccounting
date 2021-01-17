<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fab fa-buffer"></i>
                Categories
            </div>
        </div>

        <div class="card-body">
            <div>
                <div v-if="content[currency].length" class="table-responsive-xl">
                    <table :class="[
                        'responsive-table-hover',
                        darkmode ? 'table-darkmode' : 'table-lightmode'
                    ]">
                        <TableHeader
                            :cells="header"
                        ></TableHeader>

                        <tbody>
                            <CategoriesTableRow
                                v-for="(item, i) in content[currency]"
                                :key="i"
                                v-model="content[currency][i]"
                                :index="i"
                                @delete="remove(i)"
                            >
                            </CategoriesTableRow>
                        </tbody>
                    </table>
                </div>

                <EmptyPlaceholder v-else></EmptyPlaceholder>
            </div>

            <hr :class="darkmode ? 'hr-darkmode' : 'hr-lightmode'">

            <div class="row">
                <div class="col-md-4">
                    <button
                        class="big-button-primary"
                        :disabled="submitted"
                        @click="create"
                    >
                        New category
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
import CategoriesTableRow from "./CategoriesTableRow.vue";

export default {
    props: {
        darkmode: {
            required: true,
            type: Boolean
        },
        categories: {
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
        CategoriesTableRow
    },
    data() {
        return {
            header: [
                {
                    text: "Name",
                    tooltip: "The name of your category"
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
                    text: "Start date",
                    tooltip: "Count from this date"
                },
                {
                    text: "End date",
                    tooltip: "Count to this date"
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
                        const validDates = !(item.start_date && item.end_date) ||
                            new Date(item.start_date).getTime() <= new Date(item.end_date).getTime();

                        return validName && validDates;
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
            if (this.content[this.currency] === undefined) {
                this.content[this.currency] = [];
            }

            this.content[this.currency].push({
                id: 0,
                currency_id: this.currency,
                name: "",
                income_category: false,
                outcome_category: false,
                count_to_summary: false,
                start_date: "",
                end_date: ""
            })
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
                .post("/webapi/settings/categories", {
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
                $('[data-toggle="tooltip"]').tooltip()
            });
        }
    },
    beforeMount() {
        this.content = _.cloneDeep(this.categories);
        this.contentCopy = _.cloneDeep(this.categories);
    },
    mounted() {
        this.refreshTooltip();
    },
    updated() {
        this.refreshTooltip();
    }
}
</script>
