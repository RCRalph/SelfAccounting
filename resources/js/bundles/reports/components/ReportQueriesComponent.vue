<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-database"></i>
                Queries
            </div>
        </div>

        <div class="card-body">
            <div v-for="(item, i) in value" :key="i">
                <h4 class="text-center font-weight-bold mb-3">Query #{{ i + 1 }}:</h4>

                <ReportQueryComponent
                    v-model="value[i]"
                    :queryTypes="queryTypes"
                    :titles="titles"
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                ></ReportQueryComponent>

                <div class="row">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <button class="big-button-danger" @click="removeQuery(i)">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <hr class="hr-dashed" v-if="i < value.length - 1">
            </div>

            <hr class="hr" v-if="value.length">

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <button class="big-button-primary" @click="addQuery">
                        Add new query
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ReportQueryComponent from "./ReportQueryComponent.vue";

export default {
    props: {
        value: Array,
        queryTypes: Array,
        titles: Array,
        currencies: Array,
        categories: Object,
        means: Object
    },
    components: {
        ReportQueryComponent
    },
    methods: {
        addQuery() {
            return this.$emit("add");
        },
        removeQuery(i) {
            return this.$emit("remove", i);
        }
    }
}
</script>
