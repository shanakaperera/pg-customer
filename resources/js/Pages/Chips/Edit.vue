<template>
    <div>
        <div class="mb-8 flex justify-start max-w-3xl">
            <h1 class="font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('chips')">Chips</inertia-link>
                <span class="text-indigo-400 font-medium">/</span>
                Chip of Player: {{ form.player.name }}
            </h1>
        </div>
        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="update">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Player:</label>
                        <multiselect
                            v-model="form.player"
                            id="player_id"
                            label="name"
                            :options="players"
                            placeholder="Select player"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300" :limit="10"
                            @search-change="fetchPlayerData">
                            <span slot="noResult">Oops! No players found.</span>
                        </multiselect>
                        <div v-if="form.errors.player_id" class="form-error">{{ form.errors.player_id }}</div>
                    </div>
                    <text-input v-model="form.in_out" :error="form.errors.in_out" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="In/Out" />
                    <text-input v-model="form.balance" :error="form.errors.balance" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Balance" />
                    <textarea-input v-model="form.comment" :error="form.errors.comment" class="pr-6 pb-8 w-full lg:w-1/2" label="Comment" />
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                    <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Chip</button>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Chip</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'
import TextareaInput from '@/Shared/TextareaInput'
import Multiselect from 'vue-multiselect'
import axios from 'axios'

export default {
    metaInfo() {
        return {
            title: 'Edit Chip',
        }
    },
    components: {
        TextareaInput,
        LoadingButton,
        TextInput,
        Multiselect,
    },
    layout: Layout,
    props: {
        chip: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                _method: 'put',
                player: this.chip.player,
                in_out: this.chip.in_out,
                balance: this.chip.balance,
                comment: this.chip.comment,
            }),
            players: [],
        }
    },
    methods: {
        update() {
            this.form.transform((data) => ({
                '_method': 'put',
                'player_id': data.player ? data.player.id : null,
                'in_out': data.in_out,
                'balance': data.balance,
                'comment': data.comment,
            })).post(this.route('chips.update', this.chip.id), {
                onSuccess: () => console.log('Success'),
            })
        },
        destroy() {
            if (confirm('Are you sure you want to delete this chip?')) {
                this.$inertia.delete(this.route('chips.destroy', this.mile.id))
            }
        },
        fetchPlayerData(search) {
            let self = this
            // this.isLoading = true
            if (search.length > 0) {
                axios.get(this.route('pkg-players'), {params: {search: search}})
                    .then(function (response) {
                        self.players = response.data.players
                        //  self.isLoading = false
                    })
                    .catch((error) => {
                        console.log(error)
                        // self.isLoading = false
                    })
            }
        },
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.multiselect__option--highlight {
    background: #5661B3 !important;
    outline: none;
    color: white;
}
.multiselect__option--highlight:after {
    background: #5661B3 !important;
}
</style>
