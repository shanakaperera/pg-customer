<template>
    <div>
        <div class="mb-8 flex justify-start max-w-3xl">
            <h1 class="font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('players')">Players</inertia-link>
                <span class="text-indigo-400 font-medium">/</span>
                {{ form.nickname }}
            </h1>
        </div>
        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="update">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <email-input v-model="form.mail" :error="form.errors.mail" @focusOut="loadPlayer" class="pr-6 pb-8 w-full lg:w-1/2" label="Email Address"/>
                    <text-input v-model="form.nickname" :error="form.errors.nickname" class="pr-6 pb-8 w-full lg:w-1/2" label="Player Name" />
                    <text-input v-model="form.chip_balance" :error="form.errors.chip_balance" class="pr-6 pb-8 w-full lg:w-1/2" label="Tip" />
                    <text-input v-model="form.point_balance" :error="form.errors.point_balance" class="pr-6 pb-8 w-full lg:w-1/2" label="Point" />
                    <textarea-input v-model="form.comment" :error="form.errors.comment" class="pr-6 pb-8 w-full lg:w-1/2" label="Comment" />
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                    <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Player</button>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Player</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import EmailInput from '@/Shared/TextBlurInput'
import LoadingButton from '@/Shared/LoadingButton'
import TextareaInput from '@/Shared/TextareaInput'
import axios from 'axios'

export default {
    metaInfo() {
        return {
            title: this.form.nickname,
        }
    },
    components: {
        LoadingButton,
        TextInput,
        TextareaInput,
        EmailInput,
    },
    layout: Layout,
    props: {
        player: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                _method: 'put',
                mail: this.player.mail,
                nickname: this.player.nickname,
                user_id: this.player.user_id,
                chip_balance: this.player.chip_balance,
                point_balance: this.player.point_balance,
                comment: this.player.comment,
            }),
        }
    },
    methods: {
        update() {
            this.form.post(this.route('players.update', this.player.id), {
                onSuccess: () => console.log('Success'),
            })
        },
        destroy() {
            if (confirm('Are you sure you want to delete this player?')) {
                this.$inertia.delete(this.route('players.destroy', this.player.id))
            }
        },
        loadPlayer(email) {
            let self = this;
            axios.get(this.route('pkg-users.show', {'email': email}))
                .then(function (response) {
                    let user = response.data.user
                    if (user) {
                        self.form.nickname = user.username
                        self.form.user_id = user.id
                    } else {
                        self.form.nickname = null
                        self.form.user_id = null
                    }
                })
                .catch((error) => {
                    console.log(error)
                })
        },
    },
}
</script>
