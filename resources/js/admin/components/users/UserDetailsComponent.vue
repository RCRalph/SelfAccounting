<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-users"></i>
                Edit User
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <UserDataChange
                    :darkmode="darkmode"
                    :userData="userData"
                    @userDataReset="userDataReset"
                ></UserDataChange>

                <hr :class="[
                    darkmode ? 'hr-darkmode' : 'hr-lightmode',
                    'my-4'
                ]">

                <div class="row">
                    <div class="col-xl-4 d-flex justify-content-xl-end justify-content-start align-items-center">
                        <div class="h4 font-weight-bold m-xl-0">Bundles</div>
                    </div>

                    <div class="col-xl-7">
                        <multiselect
                            v-model="userData.bundles"
                            :options="bundles"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Bundles"
                            label="title"
                            track-by="id"
                            :preselect-first="false"
                        >
                            <template
                                slot="selection"
                                slot-scope="{ values, ignored, isOpen }"
                            >
                                <span
                                    class="multiselect__single"
                                    v-if="values.length && !isOpen"
                                    :id="ignored"
                                >
                                    {{ values.length }} bundle{{ values.length != 1 ? 's' : '' }}
                                </span>
                            </template>
                        </multiselect>
                    </div>
                </div>

				<hr :class="darkmode ? 'hr-darkmode' : 'hr-lightmode'" style="background-color: transparent; border-top-style: dashed; border-width: 1px;">

                <SaveResetChanges
                    :disableAll="dataSubmit"
                    :spinner="dataSubmit"
                    @save="submitData"
                    @reset="resetData"
                ></SaveResetChanges>

                <div v-if="userData.id != 1">
                    <hr :class="[
                        darkmode ? 'hr-darkmode' : 'hr-lightmode',
                        'my-4'
                    ]">

                    <div class="row">
                        <div class="col-12 col-sm-6 offset-sm-3">
                            <a :href="`/admin/users/${userData.id}/delete`" role="button" class="big-button-danger">
                                <i class="fas fa-trash"></i>
                                Delete this profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4 my-2" v-else>
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
import Multiselect from 'vue-multiselect';
import UserDataChange from "./UserDataChangeComponent.vue";
import SaveResetChanges from "../../../components/SaveResetChanges.vue";

export default {
    props: ["id"],
    components: {
        Multiselect,
        UserDataChange,
        SaveResetChanges
    },
    data() {
        return {
            darkmode: false,

            userData: {},
			userDataCopy: {},
            bundles: [],

            ready: false,
            dataSubmit: false
        }
    },
    methods: {
        userDataReset() {
            const bundles = _.cloneDeep(this.userData.bundles);
            this.userData = _.cloneDeep(this.userDataCopy)
            this.userData.bundles = bundles;
        },
        resetData() {
            this.userData.bundles = _.cloneDeep(this.userDataCopy.bundles);
        },
        submitData() {
            this.dataSubmit = true;

            axios
                .patch(`/webapi/admin/users/${this.userData.id}/update`, {
                    bundleIDs: this.userData.bundles.map(item => item.id)
                })
                .then(response => {
                    this.userData.bundles = response.data.bundles;
                    this.userDataCopy.bundles = _.cloneDeep(response.data.bundles);
                    this.dataSubmit = false;
                })
                .catch(() => {
                    this.dataSubmit = false;
                })
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios
            .get(`/webapi/admin/users/${this.id}`, {})
            .then(response => {
                this.userData = response.data.user;
                this.userDataCopy = _.cloneDeep(response.data.user);
                this.bundles = response.data.bundles;

                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
