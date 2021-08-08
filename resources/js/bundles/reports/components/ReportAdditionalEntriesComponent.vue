<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-plus-circle"></i>
                Additional entries
            </div>
        </div>

        <div class="card-body">
            <div v-for="(item, i) in value" :key="i">
                <div class="h4 fw-bold ms-3 mb-3">Entry #{{ i + 1 }}</div>

                <CreateForm
                    v-model="value[i]"
                    :currencies="currencies"
                    :categories="categories"
                    :means="means"
                    :titles="titles"
                    :minDateRestriction="false"
                    :ignoreNegativePrice="true"
                ></CreateForm>

                <div class="row">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <button class="big-button-danger" @click="removeEntry(i)">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <hr v-if="i < value.length - 1" class="hr-dashed">
            </div>

            <hr class="hr" v-if="value.length">

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <button class="big-button-primary" @click="addEntry">
                        Add new entry
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CreateForm from "../../../components/income-outcome/CreateFormComponent.vue";

export default {
    props: {
        value: Array,
        currencies: Array,
        categories: Object,
        means: Object,
        titles: Array
    },
    components: {
        CreateForm
    },
    methods: {
        addEntry() {
            this.$emit("add");
        },
        removeEntry(i) {
            this.$emit("remove", i);
        }
    }
}
</script>
