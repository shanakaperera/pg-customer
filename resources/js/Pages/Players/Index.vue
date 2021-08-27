<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Players</h1>
    <div class="mb-6 flex justify-between items-center">
        <div class="mb-6 flex justify-between items-center">
            <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter>
            <inertia-link class="btn-indigo" :href="route('players.create')">
                <span>Create</span>
                <span class="hidden md:inline">Player</span>
            </inertia-link>
        </div>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Id</th>
          <th class="px-6 pt-6 pb-4">Nickname</th>
          <th class="px-6 pt-6 pb-4">Email Address</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Comment</th>
        </tr>
          <tr v-for="player in players.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
              <td class="border-t">
                  <inertia-link class="px-6 py-4 flex items-center" :href="route('players.edit', player.id)" tabindex="-1">
                      {{ player.id }}
                  </inertia-link>
              </td>
              <td class="border-t">
                  <inertia-link class="px-6 py-4 flex items-center" :href="route('players.edit', player.id)" tabindex="-1">
                      {{ ucfirst(player.nickname) }}
                  </inertia-link>
              </td>
              <td class="border-t">
                  <inertia-link class="px-6 py-4 flex items-center" :href="route('players.edit', player.id)" tabindex="-1">
                      {{ player.email }}
                  </inertia-link>
              </td>
              <td class="border-t">
                  <inertia-link class="px-6 py-4 flex items-center" :href="route('players.edit', player.id)" tabindex="-1">
                      {{ player.comment }}
                  </inertia-link>
              </td>
              <td class="border-t w-px">
                  <inertia-link class="px-6 py-4 flex items-center" :href="route('players.edit', player.id)" tabindex="-1">
                      <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                  </inertia-link>
              </td>
          </tr>
        <tr v-if="players.data.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No players found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="players.links" />
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
  metaInfo: { title: 'Players' },
  components: {
    Icon,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filter: String,
    players: Object,
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
        this.$inertia.get(this.route('players'), pickBy(this.form), {preserveState: true})
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
