<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">Miles</h1>
        <div class="mb-6 flex justify-between items-center">
            <div class="mb-6 flex justify-between items-center">
                <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter>
                <inertia-link class="btn-indigo" :href="route('miles.create')">
                    <span>Create</span>
                    <span class="hidden md:inline">Mile</span>
                </inertia-link>
            </div>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">Date Time</th>
                    <th class="px-6 pt-6 pb-4">User</th>
                    <th class="px-6 pt-6 pb-4">Player</th>
                    <th class="px-6 pt-6 pb-4">Increase/Decrease</th>
                    <th class="px-6 pt-6 pb-4">Commission</th>
                    <th class="px-6 pt-6 pb-4" colspan="2">Comment</th>
                </tr>
                <tr v-for="mile in miles.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t flex items-center">
                        <span class="px-6 py-4 flex items-center">{{ mile.created }}</span>
                    </td>
                    <td class="border-t">
                        <span class="px-6 py-4 flex items-center">{{ mile.user ? mile.user.id + ': ' + mile.user.username : 'N/A' }}</span>
                    </td>
                    <td class="border-t">
                        <span class="px-6 py-4 flex items-center">{{ mile.player ? mile.player.id + ': ' + mile.player.nickname : 'N/A' }}</span>
                    </td>
                    <td class="border-t">
                        <span class="px-6 py-4 flex items-center">{{ mile.point }}</span>
                    </td>
                    <td class="border-t">
                        <span class="px-6 py-4 flex items-center">{{ mile.commission }}</span>
                    </td>
                    <td class="border-t">
                        <span class="px-6 py-4 flex items-center">{{ mile.comment }}</span>
                    </td>
                </tr>
                <tr v-if="miles.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No miles found.</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="miles.links" />
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import SearchFilter from '@/Shared/TextOnlySearchFilter'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import upperFirst from 'lodash/upperFirst'
import pickBy from 'lodash/pickBy'
import Pagination from '@/Shared/Pagination'

export default {
    metaInfo: { title: 'Miles' },
    components: {
        Pagination,
        SearchFilter,
    },
    layout: Layout,
    props: {
        filter: String,
        miles: Object,
    },
    data() {
        return {
            form: {
                search: this.filter,
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(this.route('miles'), pickBy(this.form), {preserveState: true})
            }, 150),
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        ucfirst(text) {
            return upperFirst(text)
        },
    },
}
</script>
