<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-user"></i>
                Profile
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="row">
                    <div class="col-lg-5">
                        <ProfileShowcase
                            :userData="userData"
                        ></ProfileShowcase>
                    </div>

                    <div class="col-lg-7 mt-sm-3">
                        <ProfileInfoChange
                            :userDataCopy="userData"
                        ></ProfileInfoChange>

						<hr class="hr my-3">

                        <ProfilePasswordChange></ProfilePasswordChange>

                        <hr class="hr my-3">

                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <button class="big-button-primary" @click="toggleTutorials" :disabled="!tutorialButtonReady">
                                    <div v-if="tutorialButtonReady">
                                        {{ userData.hide_all_tutorials ? "Show me tutorials again" : "Stop showing me tutorials" }}
                                    </div>

                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-else></span>
                                </button>
                            </div>
                        </div>

                        <div v-if="userData.id != 1">
                            <hr class="hr my-3">

                            <div class="row">
                                <div class="col-12 col-xl-6 offset-xl-3">
                                    <a href="/profile/delete" role="button" class="big-button-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete my profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Loading v-else></Loading>
        </div>
    </div>
</template>

<script>
import ProfileShowcase from "./ProfileShowcase.vue";
import ProfileInfoChange from "./ProfileInfoChange.vue";
import ProfilePasswordChange from "./ProfilePasswordChange.vue";
import Loading from "../Loading.vue";

export default {
    components: {
        ProfileShowcase,
        ProfileInfoChange,
        ProfilePasswordChange,
        Loading
    },
    data() {
        return {
            ready: false,
            userData: {},
            tutorialButtonReady: true
        }
    },
    methods: {
        toggleTutorials() {
            this.userData.hide_all_tutorials = !this.userData.hide_all_tutorials;
            this.tutorialButtonReady = false;

            axios
                .post("/webapi/settings/tutorials", {
                    tutorials: this.userData.hide_all_tutorials
                })
                .catch(() => this.userData.hide_all_tutorials = !this.userData.hide_all_tutorials)
                .finally(() => this.tutorialButtonReady = true)
        }
    },
    mounted() {
        axios.get("/webapi/profile", {})
            .then(response => {
                this.userData = response.data.user;
                this.ready = true;
            })
    }
}
</script>
