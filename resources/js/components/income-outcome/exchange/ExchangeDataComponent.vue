<template>
    <div>
        <InputGroup
            name="date"
            type="date"
            v-model="value.date"
            :min="mindate"
            :invalid="changed.date && !validDate"
            @input="changed.date = true"
        ></InputGroup>

        <InputGroup
            name="title"
            v-model="value.title"
            datalistName="titles"
            :datalist="titles"
            maxlength="64"
            placeholder="Your title here..."
            :invalid="changed.title && !validTitle"
            @input="changed.title = true"
        ></InputGroup>
    </div>
</template>

<script>
import InputGroup from "../../InputGroup.vue";

export default {
    props: {
        value: {
            required: true,
            type: Object
        },
        titles: {
            required: false,
            type: Array
        },
        mindate: {
            required: false,
            type: String
        }
    },
    components: {
        InputGroup
    },
    data() {
        return {
            changed: {
                date: false,
                title: false,
            }
        }
    },
    computed: {
        validDate() {
            const date = this.value.date;
            return date !== "" && !isNaN(Date.parse(date)) && new Date(date) >= new Date(this.mindate).getTime()
        },
        validTitle() {
            const title = this.value.title;
            return title.length && title.length <= 64;
        }
    }
}
</script>
