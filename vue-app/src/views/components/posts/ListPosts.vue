<template>
  <div>
    <div>
      <v-container>
        <v-row>
          <v-col
              cols="4"
              md="4">
            <v-text-field
                v-model="keyword"
                append-icon="mdi-table-search"
                label="Search...">
            </v-text-field>
          </v-col>
          <v-col
              cols="4"
              lg="4">
            <v-menu
                ref="menu1"
                v-model="menu1"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                    v-model="startDateFormatted"
                    label="Start Date"
                    persistent-hint
                    append-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on">
                </v-text-field>
              </template>
              <v-date-picker
                  v-model="startDate"
                  no-title
                  @input="menu1 = false">
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col
              cols="4"
              lg="4">
            <v-menu
                ref="menu2"
                v-model="menu2"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                    v-model="endDateFormatted"
                    label="End Date"
                    persistent-hint
                    append-icon="mdi-calendar"
                    readonly
                    :disabled="inputDisabled"
                    v-bind="attrs"
                    v-on="on">
                </v-text-field>
              </template>
              <v-date-picker
                  v-model="endDate"
                  no-title
                  @input="menu2 = false">
              </v-date-picker>
            </v-menu>
            <Errors :errors="errors.endDate" />
          </v-col>
        </v-row>
        <div>
          <v-btn class="comment-btn" depressed @click="clearFilter">
            Reset
          </v-btn>
        </div>
      </v-container>
    </div>
    <v-simple-table>
      <template v-slot:default>
        <thead slot="head">
        <tr>
          <th class="text-center col-9" @click.prevent="sortBy('title')">
            Title
            <span class="arrow" v-if="sort.direction === 'desc' && sort.field === 'title'">&uarr;</span>
            <span class="arrow" v-if="sort.direction === 'asc' && sort.field === 'title'">&darr;</span>
          </th>
          <th class="text-center" @click.prevent="sortBy('created_at')">
            Created At
            <span class="arrow" v-if="sort.direction === 'desc' && sort.field === 'created_at'">&uarr;</span>
            <span class="arrow" v-if="sort.direction === 'asc' && sort.field === 'created_at'">&darr;</span>
          </th>
          <th class="text-center">
            Actions
          </th>
        </tr>
        </thead>
        <tbody v-if="posts.data && posts.data.length > 0">
        <tr v-for="post in posts.data" :key="post.id">
          <td>{{ post.title }}</td>
          <td>{{ new Date(post.created_at).toLocaleString('ru-RU') }}</td>
          <td class="td-post-action">
            <router-link :to="{ name: 'ShowPost', params: { id: post.id }}"
                         class="button-action flex-column post-action-btn"><span
                class="mdi mdi-eye"></span></router-link>
            <router-link :to="{ name: 'EditPost', params: { id: post.id }}"
                         class="button-action flex-column post-action-btn"><span
                class="mdi mdi-pencil"></span></router-link>
            <button class="button-action flex-column post-action-delete-btn"
                    @click="deletePost(post.id)"><span class="mdi mdi-delete"></span></button>
          </td>
        </tr>
        </tbody>
        <tbody class="text-center" v-else>No Posts Found</tbody>
        <div class="text-center">
          <v-pagination v-model="pagination.current" :length="pagination.total" @input="getPosts" circle></v-pagination>
        </div>
      </template>
    </v-simple-table>
  </div>
</template>

<script>
import PostService from "@/service/PostService";
import Errors from "@/views/Errors";
import {debounce} from "lodash";

export default {
  components: {
    Errors
  },
  data() {
    return {
      posts: [],
      errors: [],
      keyword: null,
      pagination: {
        current: 1,
        total: 0
      },
      sort: {
        direction : 'asc',
        field: null,
      },
      startDate: null,
      endDate: null,
      menu1: false,
      menu2: false,
    }
  },
  created() {
    this.getPosts()
  },
  computed: {
    startDateFormatted() {
      return this.formatDate(this.startDate)
    },
    endDateFormatted() {
      return this.formatDate(this.endDate)
    },
    inputDisabled() {
      return this.startDate === null;
    }
  },
  watch: {
    keyword: debounce(function () {
      this.getPosts(this.keyword)
    }, 300),
    endDate() {
      this.getPosts(this.endDate)
    },
    startDate() {
      this.getPosts(this.startDate)
    },
  },
  methods: {
    getPosts() {
      const page = this.pagination.current ?? 1
      let params = `?page=${page}`

      if (this.keyword !== null) {
        params = params.concat(`&search=${this.keyword}`)
      }

      if (this.startDate !== null) {
        if (this.endDate !== null) {
          params = params.concat(`&startDate=${this.startDate}&endDate=${this.endDate}`)
        }
      }

      if (this.sort.field && this.sort.direction !== null) {
        params = params.concat(`&sortField=${this.sort.field}&sortDirection=${this.sort.direction}`)
      }

      PostService.list(params).then(response => {
        this.posts = response.data
        this.pagination.current = response.data.meta.current_page
        this.pagination.total = response.data.meta.last_page
        this.errors.endDate = null
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
        }
      })
    },
    deletePost(id) {
      if (confirm('Deleted post?')) {
        PostService.delete(id).then(() => {
          /*const i = this.posts.data.map(data => data.id).indexOf(id)
          this.posts.data.splice(i, 1)*/
          this.getPosts()
          console.log('Delete Success')
        });
      }
    },
    sortBy(field) {
      if (this.sort.field === field) {
        this.sort.direction = this.sort.direction === "asc" ? "desc" : "asc";
      } else {
        this.sort.field = field;
      }
      this.getPosts()
    },
    formatDate(date) {
      if (!date) return null

      const [year, month, day] = date.split('-')
      return `${month}/${day}/${year}`
    },
    clearFilter() {
      this.keyword = null
      this.endDate = null
      this.startDate = null
    }
  }
}
</script>

<style scoped lang="scss">

.td-post-action {
  display: table-cell;
  justify-content: center;
  align-items: center;
}

.post-action-btn {
  text-decoration: none;
  color: inherit;
  font-size: 20px;
}

.post-action-delete-btn {
  color: red;
  font-size: 20px;
}

.arrow {
  font-size: 20px;
  display: revert;
}

.highlighted {
  color: red;
}

</style>