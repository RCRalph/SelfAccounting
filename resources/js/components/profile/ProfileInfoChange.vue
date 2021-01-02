<template>
    <div>
        <form id="data-form" action="/profile/update" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="_token" :value="CSRF_TOKEN">
            <input type="hidden" name="_method" value="PATCH">

            <InputGroup
                type="text"
                name="username"
                v-model="data.username"
                maxlength="32"
                :invalid="!validUsername"
                placeholder="Your username here..."
            ></InputGroup>

            <InputGroup
                type="email"
                name="email"
                v-model="data.email"
                maxlength="64"
                :invalid="!validEmail"
                placeholder="Your email here..."
            ></InputGroup>

            <div class="form-group row">
                <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center">
                    <div class="h5 font-weight-bold m-md-0">Profile picture</div>
                </div>

                <div class="col-md-7 my-auto">
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
import InputGroup from "../InputGroup.vue";

export default {
    props: {
        userDataCopy: Object
    },
    components: {
        SaveResetChanges,
        InputGroup
    },
    data() {
        return {
            correctFile: true,
            data: {},
            submit: false
        }
    },
    methods: {
        resetData() {
            document.getElementById("picture").value = "";
            this.validFile = true;
            this.data = this.userDataCopy;
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
        this.data = _.cloneDeep(this.userDataCopy);
    },
	computed: {
		CSRF_TOKEN() {
			return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
        },
        validEmail() {
            const emailRegex = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            return emailRegex.test(this.data.email) && this.data.email.length <= 64;
        },
        validUsername() {
            return this.data.username.length <= 32 && this.data.username.length > 0;
        },
        disableSubmit() {
            return !(this.validUsername && this.validEmail && this.correctFile)
        }
    }
}
</script>
