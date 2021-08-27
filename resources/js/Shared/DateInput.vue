<template>
    <div>
        <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
        <datepicker :id="id" ref="input" format="yyyy-MM-dd" v-bind="$attrs" class="form-input" :class="{ error: error }" :type="type"
                    :value="value" @selected="$emit('input', toDateString($event))" />
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';

export default {
    components: {Datepicker},
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${this._uid}`
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        value: String,
        label: String,
        error: String,
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
        toDateString(date) {
            return moment(date).format('YYYY-MM-DD');
        }
    },
}
</script>
