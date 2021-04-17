<template>
    <div>
        <hr class="hr">

        <CategoriesTableComponent
            :data="data.categories"
            :currencies="currencies"
            :charts="bundles.charts"
        ></CategoriesTableComponent>

        <hr class="hr-dashed">

        <MeansTableComponent
            :data="data.means"
            :currencies="currencies"
            :charts="bundles.charts"
        ></MeansTableComponent>

        <hr class="hr-dashed">

        <IncomeOutcomeTableComponent
            title="Income"
            :data="data.income"
            :currencies="currencies"
            :categories="data.categories"
            :means="data.means"
        ></IncomeOutcomeTableComponent>

        <hr class="hr-dashed">

        <IncomeOutcomeTableComponent
            title="Outcome"
            :data="data.outcome"
            :currencies="currencies"
            :categories="data.categories"
            :means="data.means"
        ></IncomeOutcomeTableComponent>
    </div>
</template>

<script>
import CategoriesTableComponent from "./CategoriesTableComponent.vue";
import MeansTableComponent from "./MeansTableComponent.vue";
import IncomeOutcomeTableComponent from "./IncomeOutcomeTableComponent.vue";

export default {
    props: ["data", "currencies", "bundles"],
    components: {
        CategoriesTableComponent,
        MeansTableComponent,
        IncomeOutcomeTableComponent
    }
}
</script>
