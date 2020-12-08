<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-users"></i>
                Users
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="table-responsive-xl w-100">
                    <table
                        :class="[
                            'responsive-table-hover',
                            darkmode ? 'table-darkmode' : 'table-lightmode'
                        ]"
                    >
                        <thead>
                            <th scope="col" class="h3 font-weight-bold">ID</th>
                            <th scope="col" class="h3 font-weight-bold">Email Address</th>
                            <th scope="col" class="h3 font-weight-bold">Admin</th>
                            <th scope="col" class="h3 font-weight-bold">Premium expiration</th>
                        </thead>

                        <tbody>
                            <tr v-for="(item, i) in paginationData.data" :key="item.id" :id="item.id" :index="i">
                                <th scope="row" class="h5 my-auto font-weight-bold">{{ item.id }}</th>
                                <td class="h5 my-auto">{{ item.email }}</td>
                                <td class="h5 my-auto">
                                    <Slider
                                        :checked="item.admin"
                                        v-model="item.admin"
                                        @htmlElement="changeAdmin"
                                        :disabled="disabled"
                                    ></Slider>
                                </td>
                                <td class="h5 my-auto">
                                    <input class="form-date" type="date" v-model="item.premium_expiration" :disabled="disabled" @change="changeDate">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    <pagination :data="paginationData" @pagination-change-page="getPaginationData"></pagination>
                </div>
            </div>

            <div class="d-flex justify-content-center my-2" v-else>
                <div
                    class="spinner-grow"
                    role="status"
                    style="width: 3rem; height: 3rem;"
                >
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Slider from "../SliderCheckbox.vue";

export default {
    components: {
        Slider
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            disabled: false,
            paginationData: {}
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
        },
        changeAdmin(target) {
            const attributes = target.parentElement.parentElement.parentElement.parentElement.attributes;
            if (attributes.id.value != "1") {
                this.disabled = true;
                axios
                    .patch("/webapi/admin/users/admin", {
                        id: attributes.id.value,
                        status: this.paginationData.data[attributes.index.value].admin
                    })
                    .then(() => {
                        this.disabled = false;
                    })
                    .catch(() => {
                        this.paginationData.data[attributes.index.value].admin = true;
                        target.checked = true;
                        this.disabled = false;
                    })
            }
            else {
                this.paginationData.data[attributes.index.value].admin = true;
                target.checked = true;
            }
        },
        changeDate(event) {
            this.disabled = true;
            const attributes = event.currentTarget.parentElement.parentElement.attributes;

            axios
                .patch("/webapi/admin/users/premium", {
                    id: attributes.id.value,
                    date: this.paginationData.data[attributes.index.value].premium_expiration
                })
                .then(() => {
                    this.disabled = false;
                })
                .catch(() => {
                    this.paginationData.data[attributes.index.value].admin = true;
                    event.target.value = null;
                    this.disabled = false;
                })
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
