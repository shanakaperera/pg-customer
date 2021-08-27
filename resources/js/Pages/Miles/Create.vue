<template>
    <div>
        <div class="mb-8 flex justify-start max-w-3xl">
            <h1 class="font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('miles')">Miles</inertia-link>
                <span class="text-indigo-400 font-medium">/</span> Create
            </h1>
        </div>
        <div class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 xs:grid-cols-1 gap-4">
            <div class="bg-white rounded-md shadow overflow-hidden">
                <h5 class="text-xl font-normal leading-normal mt-2 ml-4 text-blueGray-800">Search Player</h5>
                <form @submit.prevent="search">
                    <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                        <text-input v-model="player_id" :error="form.errors.player_id" class="pr-6 pb-8 w-full" type="number" label="Player Id"/>
                        <div class="pr-6 pb-8 w-full">
                            <label class="form-label">Nickname:</label>
                            <multiselect
                                v-model="player_nickname"
                                id="player_nickname"
                                :show-labels="false"
                                label="name"
                                :options="players"
                                placeholder="Select player nickname"
                                track-by="id"
                                :internal-search="false"
                                :allow-empty="false"
                                :options-limit="300" :limit="10"
                                @search-change="fetchNicknameData">
                                <span slot="noResult">Oops! No players found.</span>
                            </multiselect>
                        </div>
                        <div class="pr-6 pb-8 w-full">
                            <label class="form-label">Player mail:</label>
                            <multiselect
                                v-model="player_mail"
                                id="player_mail"
                                :show-labels="false"
                                label="name"
                                :options="emails"
                                placeholder="Select player mail"
                                track-by="id"
                                :internal-search="false"
                                :allow-empty="false"
                                :options-limit="300" :limit="10"
                                @search-change="fetchMailData">
                                <span slot="noResult">Oops! No players found.</span>
                            </multiselect>
                        </div>
                    </div>
                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                        <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="resetSearchForm">Clear</button>
                        <loading-button :loading="processing" class="btn-indigo ml-auto" type="submit">Search</loading-button>
                    </div>
                </form>
            </div>
            <div class="bg-white rounded-md shadow overflow-hidden">
                <h5 class="text-xl font-normal leading-normal mt-2 ml-4 text-blueGray-800">Create Mile</h5>
                    <form @submit.prevent="store">
                        <div class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 xs:grid-cols-1 gap-4">
                            <div>
                                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                                    <ul class="list-none md:list-disc space-y-3">
                                        <li>Player ID : <span class="text-red-400">{{ player_id ? player_id : 'N/A' }}</span></li>
                                        <li>Nickname : <span class="text-red-400">{{ player_nickname ? player_nickname.name : 'N/A' }}</span></li>
                                        <li>Email : <span class="text-red-400">{{ player_mail ? player_mail.name : 'N/A' }}</span></li>
                                        <li>Player Miles : <span class="text-red-400">{{ player_points }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                                    <text-input v-model="form.point" :error="form.errors.point" class="pr-6 pb-8 w-full" type="number" label="Mile"/>
                                    <textarea-input v-model="form.comment" :error="form.errors.comment" class="pr-6 pb-8 w-full" label="Comment"/>
                                </div>
                            </div>
                        </div>
                        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                            <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Create</loading-button>
                        </div>
                    </form>
            </div>
        </div>
        <div class="bg-white rounded-md shadow overflow-hidden mt-5 w-full">
            <h5 class="text-xl font-normal leading-normal mt-2 ml-4 text-blueGray-800">Player Miles</h5>
            <table v-if="player_miles != null" class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">Date Time</th>
                    <th class="px-6 pt-6 pb-4">Miles</th>
                    <th class="px-6 pt-6 pb-4" colspan="2">Comment</th>
                    <th class="px-6 pt-6 pb-4">Action</th>
                </tr>
                <tr v-for="mile in player_miles.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t flex items-center">
                        <span class="px-6 py-4 flex items-center">{{ mile.created }}</span>
                    </td>
                    <td class="border-t">
                        <span class="px-6 py-4 flex items-center">{{ mile.point }}</span>
                    </td>
                    <td class="border-t" colspan="2">
                        <span class="px-6 py-4 flex items-center">{{ mile.comment }}</span>
                    </td>
                    <td class="border-t">
                        <span class="px-6 py-4 flex items-center">
                            <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="deleteMile(mile.id)">Delete</button>
                        </span>
                    </td>
                </tr>
                <tr v-if=" player_miles.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No miles found.</td>
                </tr>
            </table>
            <table v-else class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">Date Time</th>
                    <th class="px-6 pt-6 pb-4">Miles</th>
                    <th class="px-6 pt-6 pb-4" colspan="2">Comment</th>
                </tr>
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Search a Player to view miles.</td>
                </tr>
            </table>
        </div>
        <pagination v-if="player_miles!=null" class="mt-6" :links="player_miles.links"/>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import TextareaInput from '@/Shared/TextareaInput'
import LoadingButton from '@/Shared/LoadingButton'
import Pagination from '@/Shared/Pagination'
import Multiselect from 'vue-multiselect'
import axios from 'axios'

export default {
    metaInfo: {title: 'Create Mile'},
    components: {
        LoadingButton,
        TextInput,
        TextareaInput,
        Pagination,
        Multiselect,
    },
    layout: Layout,
    data() {
        return {
            form: this.$inertia.form({
                player_id: null,
                point: '0',
                comment: null,
            }),
            player_id: null,
            player_mail: null,
            player_nickname: null,
            processing: false,
            player_miles: null,
            player_points: 0,
            players: [],
            emails: [],
        }
    },
    methods: {
        store() {
            let self = this
            this.form.post(this.route('miles.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    self.form.reset()
                    self.search()
                },
            })
        },
        search() {
            let self = this
            self.processing = true
            axios.get(this.route('players.show'), {
                params: {
                    player_id: self.player_id,
                    player_mail: self.player_mail ? self.player_mail.name : null,
                    player_nickname: self.player_nickname ? self.player_nickname.name : null,
                },
            }).then(function (response) {
                let player = response.data.player
                let player_miles = response.data.player_miles

                self.player_id = null
                self.player_mail = null
                self.player_nickname = null
                self.player_miles = null
                self.player_points = 0

                if (player) {
                    self.player_id = player.id + ''
                    self.form.player_id = player.id + ''
                    self.player_mail = {'id': player.id, 'name': player.mail}
                    self.player_nickname = {'id': player.id, 'name': player.nickname}
                    self.player_points = player.sum_points
                }
                if (player_miles) {
                    self.player_miles = player_miles
                }
                self.processing = false
            }).catch((error) => {
                console.log(error)
                self.processing = false
            })
        },
        resetSearchForm() {
            this.player_id = null
            this.player_nickname = null
            this.player_mail = null
            this.player_points = 0
            this.player_miles = null
        },
        deleteMile(mileId) {
            let self = this;
            if (confirm('Are you sure you want to delete this mile?')) {
                this.$inertia.delete(this.route('miles.destroy', mileId), {
                    preserveScroll: true,
                    onSuccess: () => self.search()
                })
            }
        },
        fetchNicknameData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-players'), {params: {search: search, type: 'nickname'}})
                    .then(function (response) {
                        self.players = response.data.players
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        fetchMailData(search) {
            let self = this
            if (search.length > 0) {
                axios.get(this.route('pkg-players'), {params: {search: search, type: 'mail'}})
                    .then(function (response) {
                        self.emails = response.data.players
                    })
                    .catch((error) => {
                        console.log(error)
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
