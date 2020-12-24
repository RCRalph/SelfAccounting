<template>
    <form id="info-form" :action="`/admin/users/${userData.id}/update`" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" :value="CSRF_TOKEN">
        <input type="hidden" name="_method" value="PATCH">

        <div class="h1 font-weight-bold text-center mb-0">Main info</div>

        <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'" >

        <div>
            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Username</label>
                <div class="col-xl-7">
                    <input type="text" maxlength="32" v-model="userData.username" name="username" required :class="[
                        'form-control',
                        !validUsername && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Email</label>
                <div class="col-xl-7">
                    <input type="email" maxlength="64" v-model="userData.email" name="email" required :class="[
                        'form-control',
                        !validEmail && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Profile picture</label>
                <div class="col-xl-7 my-auto d-flex">
                    <input type="file" id="picture" name="picture" @change="checkFile" :class="[
                        'form-control',
                        'form-control-file',
                        !validFile && 'is-invalid'
                    ]">

                    <img :src="userData.profile_picture" style="height: 37px; width: 37px;" alt="Profile picture" :class="[
                        darkmode ? 'profile-img-darkmode' : 'profile-image-lightmode',
                        'ml-2'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xl-3 offset-xl-1 col-form-label text-xl-right">Premium expiration</label>
                <div class="col-xl-7">
                    <input type="date" class="form-control" v-model="userData.premium_expiration" name="premium_expiration">
                </div>
            </div>
        </div>

        <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'" >

        <div class="row">
            <div class="table-responsive col-md-6 offset-md-3">
                <table :class="[
                    'responsive-table-bordered',
                    'table-hover',
                    'mb-0',
                    darkmode ? 'table-darkmode' : 'table-lightmode'
                ]">
                    <tbody>
                        <tr>
                            <th scope="row">Darkmode</th>
                            <td>
                                <Slider
                                    name="darkmode"
                                    :checked="userData.darkmode"
                                    v-model="userData.darkmode"
                                ></Slider>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Admin</th>
                            <td>
                                <Slider
                                    name="admin"
                                    :checked="userData.admin"
                                    v-model="userData.admin"
                                ></Slider>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr :class="darkmode ? 'hr-darkmode-dashed' : 'hr-lightmode-dashed'" >

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

export default {
    components: {
        Slider,
        SaveResetChanges
    },
    props: {
        darkmode: Boolean,
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
