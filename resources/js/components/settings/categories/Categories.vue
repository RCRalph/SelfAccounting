<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fab fa-buffer"></i>
                Categories
            </div>
        </div>

        <div class="card-body">
            <div
                class="table-responsive-xl w-100"
                v-if="content != undefined && content.length > 0"
            >
                <table
                    :class="[
                        'responsive-table-hover',
                        darkmode ? 'table-darkmode' : 'table-lightmode'
                    ]"
                >
                    <TableHeader :cells="headerCells"></TableHeader>

                    <tbody>
                        <TableBody
                            v-for="(item, index) in content"
                            :key="index"
                            :content="item"
                            :index="index"
                            @delete="deleteRow"
                        ></TableBody>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <div class="h1 text-center">No categories found</div>
            </div>

            <hr />

            <div class="row">
                <div class="col-4">
                    <button
                        class="big-button-primary"
                        @click="$emit('data-add')"
                    >
                        New category
                    </button>
                </div>
                <div class="col-4">
                    <button
                        class="big-button-danger"
                        @click="$emit('data-reset')"
                    >
                        Reset changes
                    </button>
                </div>

                <div class="col-4">
                    <button
                        class="big-button-success"
                        @click="$emit('data-save')"
                    >
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TableHeader from "../../TableHeader.vue";
import TableBody from "./CategoriesTableContent.vue";

export default {
    props: {
        darkmode: String,
        content: Array
    },
    data() {
        return {
            headerCells: [
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
                    text: "Charts",
                    tooltip: "Display on charts"
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
            ]
        };
    },
    methods: {
        deleteRow: function(index) {
            this.$emit("data-delete", index);
        }
    },
    components: {
        TableHeader,
        TableBody
    }
};
</script>
