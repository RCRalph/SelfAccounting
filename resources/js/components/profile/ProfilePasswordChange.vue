<template>
    <div>
        <form action="/profile/password" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" :value="CSRF_TOKEN">
            <input type="hidden" name="_method" value="PATCH">

            <div class="form-group row">
                <label class="col-xl-4 col-form-label text-xl-right">New password</label>
                <div class="col-xl-7">
                    <input type="password" v-model="passwords[0]" name="password" :class="[
                        'form-control',
                        !validPasswords && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xl-4 col-form-label text-xl-right">Confirm password</label>
                <div class="col-xl-7">
                    <input type="password" v-model="passwords[1]" name="password_confirmation" :class="[
                        'form-control',
                        !validPasswords && 'is-invalid'
                    ]">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-xl-7 offset-xl-4">
                    <button type="submit" class="big-button-success" @click="submit = true" :disabled="!canSubmit || !validPasswords">
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
export default {
    data() {
        return {
            passwords: ["", ""],
            submit: false
        }
    },
    computed: {
		CSRF_TOKEN: function() {
			return document.head.querySelectorAll("meta[name=csrf-token]")[0].attributes.content.value;
        },
        validPasswords: function() {
            return (this.passwords[0].length >= 8 && this.passwords[1].length >= 8 && this.passwords[0] === this.passwords[1]) || (this.passwords[0] == "" && this.passwords[1] == "");
        },
        canSubmit: function() {
            return this.passwords[0].length > 0 && this.passwords[1].length > 0;
        }
    }
}
</script>
