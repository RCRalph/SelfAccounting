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
                <div class="shared-user">
                    <img class="shared-user-image" src="/storage/avatars/Emoji1.png" alt="Users profile picture">

                    <div class="shared-user-username">
                        Normal user
                    </div>

                    <div class="shared-user-delete">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>

                <div class="shared-user">
                    <img class="shared-user-image" src="/storage/avatars/Emoji1.png" alt="Users profile picture">

                    <div class="shared-user-username">
                        Normal user
                    </div>

                    <div class="shared-user-delete">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>

                <div class="shared-user">
                    <img class="shared-user-image" src="/storage/avatars/Emoji1.png" alt="Users profile picture">

                    <div class="shared-user-username">
                        Normal user
                    </div>

                    <div class="shared-user-delete">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>

                <div class="shared-user">
                    <img class="shared-user-image" src="/storage/avatars/Emoji1.png" alt="Users profile picture">

                    <div class="shared-user-username">
                        Normal user
                    </div>

                    <div class="shared-user-delete">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            </div>

            <hr class="hr" v-if="value.length || true">

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
                        placeholder="Enter user's Email..."
                        autocomplete="email"
                        @input="changedEmail = true"
                    >
                </div>

                <div class="col-md-4">
                    <button class="big-button-primary mt-2 mt-md-0" @click="addUser">
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
        }
    },
    computed: {
        validEmail() {
            /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(this.email);
        }
    },
    methods: {
        addUser() {
            return this.$emit("add", this.email);
        },
        removeUser(i) {
            return this.$emit("remove", i);
        }
    }
}
</script>
