<template>
    <div>
        <form id="data-form" action="/profile/update" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="_token" :value="CSRF_TOKEN">
            <input type="hidden" name="_method" value="PATCH">

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Username</label>
                <div class="col-xl-7">
                    <input type="text" id="username" maxlength="32" v-model="userData.username" name="username" required :class="[
                        'form-control',
                        !validUsername && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Email</label>
                <div class="col-xl-7">
                    <input type="text" id="email" maxlength="64" v-model="userData.email" name="email" required :class="[
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

            <SaveResetChanges
                :disableAll="submit"
                :disableSave="disableSubmit"
                :spinner="submit"
                @save="submitForm"
                @reset="resetData"
            ></SaveResetChanges>
        </form>
    </div>
</template>

<script>
import SaveResetChanges from "../SaveResetChanges.vue";

export default {
    props: {
        userDataCopy: Object
    },
    components: {
        SaveResetChanges
    },
    data() {
        return {
            correctFile: true,
            userData: {},
            submit: false
        }
    },
    methods: {
        resetData() {
            document.getElementById("picture").value = "";
            this.validFile = true;
            this.userData = this.userDataCopy;
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
        this.userData = _.cloneDeep(this.userDataCopy);
    },
	computed: {
		CSRF_TOKEN() {
			return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
        },
        validEmail() {
            const emailRegex = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            return emailRegex.test(this.userData.email) && this.userData.email.length <= 64;
        },
        validUsername() {
            return this.userData.username.length <= 32 && this.userData.username.length > 0;
        },
        disableSubmit() {
            return !(this.validUsername && this.validEmail && this.correctFile)
        }
    }
}
</script>
