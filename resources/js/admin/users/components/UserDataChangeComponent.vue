<template>
    <form id="info-form" :action="`/admin/users/${userData.id}/update`" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" :value="CSRF_TOKEN">
        <input type="hidden" name="_method" value="PATCH">

        <div class="h1 font-weight-bold text-center mb-0">Main info</div>

        <hr class="hr-dashed">

        <div>
            <InputGroup
                type="text"
                name="username"
                v-model="userData.username"
                :invalid="!validUsername"
                maxlength="32"
                placeholder="Enter username here..."
            ></InputGroup>

            <InputGroup
                type="email"
                maxlength="64"
                name="email"
                v-model="userData.email"
                :invalid="!validEmail"
            ></InputGroup>

            <div class="input-group-row">
                <div class="col-md-4 d-flex justify-content-md-end justify-content-start align-items-center">
                    <div class="h5 font-weight-bold m-md-0">Profile picture</div>
                </div>

                <div class="col-md-7 my-auto d-flex">
                    <input type="file" id="picture" name="picture" @change="checkFile" :class="[
                        'form-control',
                        'form-control-file',
                        !validFile && 'is-invalid'
                    ]">

                    <img :src="userData.profile_picture" style="height: 37px; width: 37px;" alt="Profile picture" class="profile-img ms-2">
                </div>
            </div>

            <InputGroup
                type="date"
                name="premium_expiration"
                v-model="userData.premium_expiration"
            ></InputGroup>
        </div>

        <hr class="hr-dashed">

        <div class="row">
            <div class="table-responsive col-md-6 offset-md-3">
                <table class="table-themed responsive-table-bordered table-hover mb-0">
                    <tbody>
                        <tr>
                            <th scope="row">Darkmode</th>
                            <td>
                                <Slider
                                    name="darkmode"
                                    v-model="userData.darkmode"
                                ></Slider>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Admin</th>
                            <td>
                                <Slider
                                    name="admin"
                                    v-model="userData.admin"
                                ></Slider>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="hr-dashed">

        <SaveResetChanges
            :disableAll="submit"
            :disableSave="disableSubmit"
            :spinner="submit"
            @save="submitForm"
            @reset="resetData"
        ></SaveResetChanges>
    </form>
</template>

<script>
import Slider from "../../../components/SliderCheckbox.vue";
import SaveResetChanges from "../../../components/SaveResetChanges.vue";
import InputGroup from "../../../components/InputGroup.vue";

export default {
    components: {
        Slider,
        SaveResetChanges,
        InputGroup
    },
    props: {
        userData: Object,
    },
    data() {
        return {
            validFile: true,
            submit: false
        }
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
            return !(this.validUsername && this.validEmail && this.validFile)
        }
    },
    methods: {
        submitForm() {
            this.submit = true;
            document.getElementById("info-form").submit();
        },
        resetData() {
            this.$emit("userDataReset");
            this.validFile = true;
            document.getElementById("picture").value = "";
        },
        checkFile() {
            this.validFile = document.getElementById("picture").files[0].type.includes("image");
        }
    }
}
</script>
