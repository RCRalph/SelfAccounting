<template>
    <div>
        <form id="password-form" action="/profile/password" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" :value="CSRF_TOKEN">
            <input type="hidden" name="_method" value="PATCH">

            <InputGroup
                type="password"
                name="password"
                v-model="passwords[0]"
                :invalid="!validPasswords"
                placeholder="Your password here..."
                autocomplete="off"
            ></InputGroup>

            <InputGroup
                type="password"
                name="password_confirmation"
                v-model="passwords[1]"
                :invalid="!validPasswords"
                placeholder="Confirm your password here..."
                autocomplete="off"
            ></InputGroup>

            <div class="input-group-row">
                <div class="col-xl-7 offset-xl-4">
                    <button type="button" class="big-button-success" @click="submitForm" :disabled="!canSubmit || !validPasswords">
                        <div v-if="!submit">
                            Save changes
                        </div>

                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-else></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import InputGroup from "../InputGroup.vue";

export default {
    components: {
        InputGroup
    },
    data() {
        return {
            passwords: ["", ""],
            submit: false
        }
    },
    methods: {
        submitForm() {
            document.getElementById("password-form").submit();
            this.submit = true;
        }
    },
    computed: {
		CSRF_TOKEN() {
			return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
        },
        validPasswords() {
            return (this.passwords[0].length >= 8 && this.passwords[1].length >= 8 && this.passwords[0] === this.passwords[1]) || (this.passwords[0] == "" && this.passwords[1] == "");
        },
        canSubmit() {
            return this.passwords[0].length > 0 && this.passwords[1].length > 0;
        }
    }
}
</script>
