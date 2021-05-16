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

        <div v-if="data.bundleData.cash != undefined && data.bundleData.reports.length">
            <hr class="hr-dashed">

            <CashTableComponent
                :cash="data.bundleData.cash"
                :cashMeans="data.bundleData.cashMeans"
                :currencies="currencies"
                :means="data.means"
            ></CashTableComponent>
        </div>

        <div v-if="data.bundleData.reports != undefined && data.bundleData.reports.length">
            <hr class="hr-dashed">

            <ReportsTableComponent
                :data="data.bundleData.reports"
                :currencies="currencies"
                :categories="data.categories"
                :means="data.means"
            ></ReportsTableComponent>
        </div>
    </div>
</template>

<script>
import CategoriesTableComponent from "./CategoriesTableComponent.vue";
import MeansTableComponent from "./MeansTableComponent.vue";
import IncomeOutcomeTableComponent from "./IncomeOutcomeTableComponent.vue";
import CashTableComponent from "./CashTableComponent.vue";
import ReportsTableComponent from "./ReportsTableComponent.vue";

export default {
    props: ["data", "currencies", "bundles"],
    components: {
        CategoriesTableComponent,
        MeansTableComponent,
        IncomeOutcomeTableComponent,
        CashTableComponent,
        ReportsTableComponent
    }
}
</script>
