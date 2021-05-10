<template>
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-share-alt"></i>
                Users shared with
            </div>
        </div>

        <div class="card-body">
            <div class="shared-users-wrapper">
                <div class="shared-user" v-for="(item, i) in value" :key="i">
                    <img class="shared-user-image" :src="item.profile_picture" :alt="`Profile picture of ${item.username}`">

                    <div class="shared-user-username">
                        {{ item.username }}
                    </div>

                    <div class="shared-user-delete" @click="removeUser(i)">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            </div>

            <hr class="hr-dashed" v-if="value.length && awaitingConfirmation.length">

            <div class="shared-users-wrapper">
                <div class="shared-user-waiting" v-for="(item, i) in awaitingConfirmation" :key="i + value.length">
                    <div class="shared-user-waiting-email">
                        {{ item.email }}
                    </div>

                    <i class="fas fa-exclamation-circle shared-user-waiting-error" v-if="item.error"></i>

                    <span
                        v-else
                        class="shared-user-waiting-spinner"
                        role="status"
                        aria-hidden="true"
                    ></span>
                </div>
            </div>

            <hr class="hr" v-if="value.length || awaitingConfirmation.length">

            <div class="row">
                <div class="col-md-3 d-flex justify-content-md-end justify-content-start align-items-center">
                    <div class="h5 font-weight-bold m-md-0">User's email:</div>
                </div>

                <div class="col-md-5">
                    <input
                        type="email"
                        v-model="email"
                        :class="[
                            'form-control',
                            changedEmail && !validEmail && 'is-invalid'
                        ]"
                        maxlength="64"
                        placeholder="Enter user's email..."
                        autocomplete="email"
                        @input="changedEmail = true"
                    >

                    <span v-if="emailErrorMessage" class="invalid-feedback" role="alert">
                        <strong>{{ emailErrorMessage }}</strong>
                    </span>
                </div>

                <div class="col-md-4">
                    <button class="big-button-primary mt-2 mt-md-0" @click="addUser" :disabled="!validEmail">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: Array
    },
    data() {
        return {
            email: "",
            changedEmail: false,
            awaitingConfirmation: [],
            emailErrorMessage: ""
        }
    },
    computed: {
        validEmail() {
            const emailRegex = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            const validEmail = emailRegex.test(this.email) && this.email.length <= 64;
            const alreadyInserted = this.awaitingConfirmation.includes(this.email) ||
                this.value.map(item => item.email).includes(this.email);

            this.emailErrorMessage = (
                !validEmail ? "Invalid email" :
                    (alreadyInserted ? "This user is already in the list" : "")
            );

            return validEmail && !alreadyInserted;
        }
    },
    methods: {
        addUser() {
            const email = this.email;
            this.email = "";
            this.changedEmail = false;
            this.awaitingConfirmation.push({
                email,
                error: false
            });

            axios
                .get("/webapi/bundles/reports/get-user-info", {
                    params: {
                        email
                    }
                })
                .then(response => {
                    this.value.push({
                        email,
                        username: response.data.username,
                        profile_picture: response.data.profile_picture
                    });

                    const index = this.awaitingConfirmation
                        .findIndex(item => item.email == email);
                    this.awaitingConfirmation.splice(index, 1);
                })
                .catch(err => {
                    console.error(err);

                    const index = this.awaitingConfirmation
                        .findIndex(item => item.email == email);
                    this.awaitingConfirmation[index].error = true;

                    setTimeout(() => this.awaitingConfirmation.splice(index, 1), 2000);
                })
        },
        removeUser(i) {
            this.value.splice(i, 1);
        }
    }
}
</script>
