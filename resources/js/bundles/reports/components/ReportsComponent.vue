<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-newspaper"></i>
                Reports
            </div>

            <div class="d-flex" v-if="ready">
                <a role="button" class="big-button-primary" href="/bundles/reports/create">
                    New Report
                </a>
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="card" v-if="userReports.total">
                    <div class="card-header-flex">
                        <div class="card-header-text">
                            <i class="fas fa-file-alt"></i>
                            My reports
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive w-100" v-if="userReportsReady">
                            <table
                                class="responsive-table-hover table-themed"
                            >
                                <thead>
                                    <th scope="col" class="h3 font-weight-bold">ID</th>
                                    <th scope="col" class="h3 font-weight-bold">Title</th>
                                    <th scope="col" class="h3 font-weight-bold">View</th>
                                    <th scope="col" class="h3 font-weight-bold">Edit</th>
                                </thead>

                                <tbody>
                                    <tr v-for="(item, i) in userReports.data" :key="item.id" :id="item.id" :index="i">
                                        <th scope="row" class="h5 my-auto font-weight-bold">{{ item.id }}</th>

                                        <td class="h5 my-auto">{{ item.title }}</td>

                                        <td class="h5 my-auto">
                                            <a role="button" class="big-button-primary" :href="`/bundles/reports/${item.id}`">View report</a>
                                        </td>

                                        <td class="h5 my-auto">
                                            <a role="button" class="big-button-primary" :href="`/bundles/reports/${item.id}/edit`">Edit report</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center">
                                <pagination :data="userReports" @pagination-change-page="getUserReports"></pagination>
                            </div>
                        </div>

                        <Loading v-else></Loading>
                    </div>
                </div>

                <hr class="hr" v-if="userReports.total && sharedReports.total">

                <div class="card" v-if="sharedReports.total">
                    <div class="card-header-flex">
                        <div class="card-header-text">
                            <i class="fas fa-share-alt"></i>
                            Reports shared with me
                        </div>
                    </div>

                    <div class="card-body">
                        <div v-if="sharedReportsReady">
                            <div class="table-responsive w-100">
                                <table
                                    class="responsive-table-hover table-themed"
                                >
                                    <thead>
                                        <th scope="col" class="h3 font-weight-bold">ID</th>
                                        <th scope="col" class="h3 font-weight-bold">Title</th>
                                        <th scope="col" class="h3 font-weight-bold">View</th>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(item, i) in sharedReports.data" :key="item.id" :index="i">
                                            <th scope="row" class="h5 my-auto font-weight-bold">{{ item.id }}</th>

                                            <td class="h5 my-auto">{{ item.title }}</td>

                                            <td class="h5 my-auto">
                                                <a role="button" class="big-button-primary" :href="`/bundles/reports/${item.id}`">View report</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">
                                <pagination :data="sharedReports" @pagination-change-page="getSharedReports"></pagination>
                            </div>
                        </div>

                        <Loading v-else></Loading>
                    </div>
                </div>

                <EmptyPlaceholder v-if="!sharedReports.total && !userReports.total"></EmptyPlaceholder>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading.vue";
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";

export default {
    components: {
        Loading,
        EmptyPlaceholder
    },
    data() {
        return {
            ready: false,

            userReports: {},
            userReportsReady: false,

            sharedReports: {},
            sharedReportsReady: false
        }
    },
    methods: {
        getUserReports(pageNumber = 1) {
            this.userReportsReady = false;

            axios
                .get("/webapi/bundles/reports/user-reports", {
                    params: {
                        page: pageNumber
                    }
                })
                .then(response => {
                    this.userReports = response.data.reports;
                    this.userReportsReady = true;

                    if (this.sharedReportsReady) {
                        this.ready = true;
                    }
                })
                .catch(err => {
                    console.error(err);
                })
        },
        getSharedReports(pageNumber = 1) {
            this.sharedReportsReady = false;

            axios
                .get("/webapi/bundles/reports/shared-reports", {
                    params: {
                        page: pageNumber
                    }
                })
                .then(response => {
                    this.sharedReports = response.data.reports;
                    this.sharedReportsReady = true;

                    if (this.userReportsReady) {
                        this.ready = true;
                    }
                })
                .catch(err => {
                    console.error(err);
                })
        }
    },
    mounted() {
        this.getUserReports();
        this.getSharedReports();
    }
}
</script>
