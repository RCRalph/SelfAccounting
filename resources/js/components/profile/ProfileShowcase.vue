<template>
    <div :class="darkmode ? 'profile-wrapper-dark' : 'profile-wrapper-light'">
        <div class="profile-picture">
            <img :src="userData.profile_picture" :alt="userData.username">
        </div>

        <p :class="[
                'username',
                premiumObject.type && 'premium'
            ]"
        >
            {{ userData.username }}
        </p>

        <hr>

        <div>
            <p :class="[
                    'account-type',
                    premiumObject.type && 'premium'
                ]"
            >
                Account type: {{ userTypes[premiumObject.type] }}
            </p>

            <p class="account-type premium" v-if="premiumObject.type && premiumObject.expiration">
                Premium left:<br>
                {{ premiumObject.expiration }} {{ premiumObject.expiration == 1 ? 'day' : 'days' }}
            </p>
        </div>

        <hr>

        <p class="sa-since">
            With us since:<br>
            {{ createDate }}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        darkmode: Boolean,
        userData: Object
    },
    data() {
        return {
            userTypes: ["Basic", "Premium", "Admin"]
        }
    },
    computed: {
        createDate: function() {
            const date = new Date(this.userData.created_at);
            return `${date.getFullYear()}-${(date.getMonth() + 1) < 10 ? '0' : ''}${date.getMonth() + 1}-${date.getDate() < 10 ? '0' : ''}${date.getDate()}`;
        },
        premiumObject: function() {
            if (this.userData.admin) {
                return {
                    type: 2
                }
            }
            if (this.userData.premium_expiration === null) {
                return {
                    expiration: false,
                    type: 1
                };
            }
            else {
                const MILISECONDS_IN_DAY = 24 * 60 * 60 * 1000;
                const date = new Date(this.userData.premium_expiration);
                if (new Date().getTime() - MILISECONDS_IN_DAY < date.getTime()) {
                    return {
                        expiration: Math.ceil((date.getTime() - new Date().getTime()) / MILISECONDS_IN_DAY) + 1,
                        type: 1
                    };
                }
                else {
                    return {
                        type: 0
                    };
                }
            }
        }
    }
}
</script>
