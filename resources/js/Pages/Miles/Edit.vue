<template>
    <div>
        <div class="mb-8 flex justify-start max-w-3xl">
            <h1 class="font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('miles')">Miles</inertia-link>
                <span class="text-indigo-400 font-medium">/</span>
                Mile of Player: {{ form.player.name }}
            </h1>
        </div>
        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="update">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <select-input v-model="form.enable" :error="form.errors.enable" class="pr-6 pb-8 w-full lg:w-1/2" label="Status">
                        <option :value="0">Invalid</option>
                        <option :value="1">Valid</option>
                    </select-input>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">User:</label>
                        <multiselect
                            v-model="form.user"
                            id="user_id"
                            label="name"
                            :options="users"
                            placeholder="Select user"
                            track-by="id"
                            :internal-search="false"
                            :allow-empty="false"
                            :options-limit="300" :limit="10"
                            @search-change="fetchUserData">
                            <span slot="noResult">Oops! No users found.</span>
                        </multiselect>
                        <div v-if="form.errors.user_id" class="form-error">{{ form.errors.user_id }}</div>
                    </div>
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
                    <text-input v-model="form.point" :error="form.errors.point" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Increase/Decrease" />
                    <text-input v-model="form.user_commission" :error="form.errors.user_commission" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="User Commission" />
                    <text-input v-model="form.venue_commission" :error="form.errors.venue_commission" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Store Commission" />
                    <textarea-input v-model="form.comment" :error="form.errors.comment" class="pr-6 pb-8 w-full lg:w-1/2" label="Comment" />
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                    <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Mile</button>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Mile</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TextareaInput from '@/Shared/TextareaInput'
import Multiselect from 'vue-multiselect'
import axios from 'axios'

export default {
    metaInfo() {
        return {
            title: 'Edit Mile',
        }
    },
    components: {
        TextareaInput,
        LoadingButton,
        SelectInput,
        TextInput,
        Multiselect,
    },
    layout: Layout,
    props: {
        mile: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                _method: 'put',
                enable: this.mile.enable,
                player: this.mile.player,
                user: this.mile.user,
                point: this.mile.point,
                user_commission: this.mile.user_commission,
                venue_commission: this.mile.venue_commission,
                comment: this.mile.comment,
            }),
            users: [],
            players: [],
        }
    },
    methods: {
        update() {
            this.form.transform((data) => ({
                '_method': 'put',
                'enable': data.enable,
                'player_id': data.player ? data.player.id : null,
                'user_id': data.user ? data.user.id : null,
                'point': data.point,
                'user_commission': data.user_commission,
                'venue_commission': data.venue_commission,
                'comment': data.comment,
            })).post(this.route('miles.update', this.mile.id), {
                onSuccess: () => console.log('Success'),
            })
        },
        destroy() {
            if (confirm('Are you sure you want to delete this mile?')) {
                this.$inertia.delete(this.route('miles.destroy', this.mile.id))
            }
        },
        fetchUserData(search) {
            let self = this
            // this.isLoading = true
            if (search.length > 0) {
                axios.get(this.route('pkg-users'), {params: {search: search}})
                    .then(function (response) {
                        self.users = response.data.users
                        //  self.isLoading = false
                    })
                    .catch((error) => {
                        console.log(error)
                        // self.isLoading = false
                    })
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
