<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-users"></i>
                Users
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div v-if="paginationData.data.length">
                    <div class="table-responsive-xl w-100">
                        <table class="responsive-table-hover table-themed">
                            <thead>
                                <th scope="col" class="h3 font-weight-bold">ID</th>
                                <th scope="col" class="h3 font-weight-bold">Email Address</th>
                                <th scope="col" class="h3 font-weight-bold">Details</th>
                            </thead>

                            <tbody>
                                <tr v-for="(item, i) in paginationData.data" :key="item.id" :id="item.id" :index="i">
                                    <th scope="row" class="h5 my-auto font-weight-bold">{{ item.id }}</th>
                                    <td class="h5 my-auto">{{ item.email }}</td>
                                    <td class="h5 my-auto">
                                        <a role="button" class="big-button-primary" :href="'/admin/users/' + item.id">View details</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <EmptyPlaceholder v-else></EmptyPlaceholder>

                <div class="d-flex justify-content-center">
                    <pagination :data="paginationData" @pagination-change-page="getPaginationData"></pagination>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import EmptyPlaceholder from "../../../components/EmptyPlaceholder.vue";
import Loading from "../../../components/Loading.vue";

export default {
    components: {
        EmptyPlaceholder,
        Loading
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            paginationData: {},
        };
    },
    methods: {
        getPaginationData(pageNumber = 1) {
            this.ready = false;
            axios
                .get("/webapi/admin/users", {
                    params: {
                        page: pageNumber
                    }
                })
                .then(response => {
                    this.paginationData = response.data.users;
                    this.ready = true;
                });
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        this.getPaginationData();
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
