<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-info-circle"></i>
                User - Details
            </div>
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right form-control-lg">
                    Enter the user's ID:
                </label>

                <div class="col-md-4">
                    <input type="text" v-model="enteredId" @keyup.enter="getUserData" :class="[
                        'form-control',
                        'form-control-lg',
                        'text-center',
                        !correctNumber && 'is-invalid'
                    ]">
                </div>

                <div class="col-md-4 mt-3 mt-md-0">
                    <button class="big-button-primary h-100 btn-lg" @click="getUserData" :disabled="!correctNumber || enteredId === ''">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>

                <div v-if="error" class="text-center col-12 mt-2 text-danger h5 font-weight-bold">
                    User not found
                </div>
            </div>

            <hr v-if="ready && !error" :class="[
                darkmode ? 'hr-darkmode' : 'hr-lightmode',
                'my-4'
            ]">

            <div v-if="ready && !error">
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

				<div class="row">
					<div class="col-sm-6 my-2 my-sm-0">
						<button type="button" class="big-button-success" @click="submitData" :disabled="dataSubmit">
							<div v-if="!dataSubmit">
								Save changes
							</div>
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-else></span>
						</button>
					</div>

					<div class="col-sm-6 my-2 my-sm-0">
						<button type="button" class="big-button-danger" @click="dataReset" :disabled="dataSubmit">Reset changes</button>
					</div>
				</div>

                <div v-if="userData.id != 1">
                    <hr :class="[
                        darkmode ? 'hr-darkmode' : 'hr-lightmode',
                        'my-4'
                    ]">

                    <div class="row">
                        <div class="col-12 col-sm-6 offset-sm-3">
                            <a :href="'/admin/users/delete?id=' + userData.id" role="button" class="big-button-danger">
                                <i class="fas fa-trash"></i>
                                Delete this profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4 my-2" v-if="startedSearch">
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

export default {
    props: ["start"],
    components: {
        Multiselect,
        UserDataChange
    },
    data() {
        return {
            darkmode: false,
            enteredId: "",

            userData: {},
			userDataCopy: {},
            bundles: [],

            ready: false,
            startedSearch: false,
            error: false,

            dataSubmit: false
        }
    },
    computed: {
        correctNumber() {
            const numberToCheck = Number(this.enteredId);
            return this.enteredId === "" || (!isNaN(numberToCheck) && numberToCheck == Math.floor(numberToCheck) && numberToCheck > 0);
        }
    },
    methods: {
        getUserData() {
            this.startedSearch = true;
            this.ready = false;
            axios
                .get("/webapi/admin/users/details", {
                    params: {
                        id: Number(this.enteredId)
                    }
                })
                .then(response => {
					this.userData = response.data.user;
                    this.userDataCopy = _.cloneDeep(response.data.user);
                    this.bundles = response.data.bundles;

                    this.startedSearch = false;
                    this.ready = true;
                    this.error = false;
                })
                .catch(() => {
                    this.error = true;
                    this.startedSearch = false;
                    this.ready = true;
                })
        },
        userDataReset() {
            const bundles = _.cloneDeep(this.userData.bundles);
            this.userData = _.cloneDeep(this.userDataCopy)
            this.userData.bundles = bundles;
        },
        dataReset() {
            this.userData.bundles = _.cloneDeep(this.userDataCopy.bundles);
        },
        submitData() {
            this.dataSubmit = true;

            axios
                .patch("/webapi/admin/users/details/update", {
                    id: this.userData.id,
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
        if (this.start) {
            this.enteredId = Number(this.start);
            this.getUserData();
        }
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
