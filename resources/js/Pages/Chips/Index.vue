<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">Chips</h1>
        <div class="mb-6 flex justify-between items-center">
            <div class="mb-6 flex justify-between items-center">
                <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter>
                <inertia-link class="btn-indigo" :href="route('chips.create')">
                    <span>Create</span>
                    <span class="hidden md:inline">Chip</span>
                </inertia-link>
            </div>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">Created</th>
                    <th class="px-6 pt-6 pb-4">Player</th>
                    <th class="px-6 pt-6 pb-4">In/Out</th>
                    <th class="px-6 pt-6 pb-4">Balance</th>
                    <th class="px-6 pt-6 pb-4" colspan="2">Comment</th>
                </tr>
                <tr v-for="chip in chips.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('chips.edit', chip.id)" tabindex="-1">
                            {{ chip.created }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('chips.edit', chip.id)" tabindex="-1">
                            {{ chip.player ? chip.player.id + ': ' + chip.player.nickname : 'n/A' }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('chips.edit', chip.id)" tabindex="-1">
                            {{ chip.in_out }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('chips.edit', chip.id)" tabindex="-1">
                            {{ chip.balance }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('chips.edit', chip.id)" tabindex="-1">
                            {{ chip.comment }}
                        </inertia-link>
                    </td>
                    <td class="border-t w-px">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('chips.edit', chip.id)" tabindex="-1">
                            <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                        </inertia-link>
                    </td>
                </tr>
                <tr v-if="chips.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No chips found.</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="chips.links" />
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import SearchFilter from '@/Shared/TextOnlySearchFilter'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import upperFirst from 'lodash/upperFirst'
import pickBy from 'lodash/pickBy'
import Icon from '@/Shared/Icon'
import Pagination from '@/Shared/Pagination'

export default {
    metaInfo: { title: 'Chips' },
    components: {
        Icon,
        Pagination,
        SearchFilter,
    },
    layout: Layout,
    props: {
        filter: String,
        chips: Object,
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
                this.$inertia.get(this.route('chips'), pickBy(this.form), {preserveState: true})
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
