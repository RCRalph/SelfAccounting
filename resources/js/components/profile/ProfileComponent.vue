<template>
    <div :class="darkmode ? 'dark-card' : 'card'">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-user"></i>
                Profile
            </div>
        </div>

        <div class="card-body">
            <div v-if="ready">
                <div class="row">
                    <div class="col-lg-6 col-xl-5">
                        <ProfileShowcase
                            :darkmode="darkmode"
                            :userData="userData"
                        ></ProfileShowcase>
                    </div>
                    <div class="col-lg-6 col-xl-7 mt-sm-3">
                        <ProfileInfoChange
                            :userDataCopy="userData"
                        ></ProfileInfoChange>

						<hr :class="[
                            darkmode ? 'hr-darkmode' : 'hr-lightmode',
                            'my-3'
                        ]">

                        <ProfilePasswordChange></ProfilePasswordChange>

                        <div v-if="userData.id != 1">
                            <hr :class="[
                                darkmode ? 'hr-darkmode' : 'hr-lightmode',
                                'my-3'
                            ]">

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
import ProfileShowcase from "./ProfileShowcase.vue";
import ProfileInfoChange from "./ProfileInfoChange.vue";
import ProfilePasswordChange from "./ProfilePasswordChange.vue";

export default {
    components: {
        ProfileShowcase,
        ProfileInfoChange,
        ProfilePasswordChange
    },
    data() {
        return {
            darkmode: false,
            ready: false,
            userData: {},
        }
    },
    beforeMount() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    },
    mounted() {
        axios.get("/webapi/profile", {})
            .then(response => {
                this.userData = response.data.user;
                this.ready = true;
            })
    },
    beforeUpdate() {
        this.darkmode = document.getElementById("darkmode-status").innerHTML.includes("1");
    }
}
</script>
