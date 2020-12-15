<template>
    <div>
        <form id="data-form" action="/profile/update" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="_token" :value="CSRF_TOKEN">
            <input type="hidden" name="_method" value="PATCH">

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Username</label>
                <div class="col-xl-7">
                    <input type="text" id="username" maxlength="32" v-model="userDataCopy.username" name="username" required :class="[
                        'form-control',
                        !validUsername && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Email</label>
                <div class="col-xl-7">
                    <input type="text" id="email" maxlength="64" v-model="userDataCopy.email" name="email" required :class="[
                        'form-control',
                        !validEmail && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Profile picture</label>
                <div class="col-xl-7 my-auto">
                    <input type="file" id="picture" name="picture" @change="checkFile" :class="[
                        'form-control',
                        'form-control-file',
                        !correctFile && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 my-2 my-sm-0">
                    <button type="button" class="big-button-success" @click="submitForm" :disabled="disableSubmit || submit">
                        <div v-if="!submit">
                            Save changes
                        </div>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-else></span>
                    </button>
                </div>

                <div class="col-sm-6 my-2 my-sm-0">
                    <button type="button" class="big-button-danger" @click="dataReset" :disabled="submit">Reset changes</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        userData: Object
    },
    data() {
        return {
            correctFile: true,
            userDataCopy: {},
            submit: false
        }
    },
    methods: {
        dataReset() {
            document.getElementById("picture").value = "";
            this.validFile = true;
            this.userDataCopy = this.userData;
        },
        checkFile() {
            const fileType = document.getElementById("picture").files[0].type;
            this.correctFile = fileType.includes("image");
        },
        submitForm() {
            document.getElementById("data-form").submit();
            this.submit = true;
        }
    },
    beforeMount() {
        this.userDataCopy = _.cloneDeep(this.userData);
    },
	computed: {
		CSRF_TOKEN() {
			return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
        },
        validEmail() {
            const emailRegex = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            return emailRegex.test(this.userDataCopy.email) && this.userDataCopy.email.length <= 64;
        },
        validUsername() {
            return this.userDataCopy.username.length <= 32 && this.userDataCopy.username.length > 0;
        },
        disableSubmit() {
            return !(this.validUsername && this.validEmail && this.correctFile)
        }
    }
}
</script>
