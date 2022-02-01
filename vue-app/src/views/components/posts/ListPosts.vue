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
                prepend-icon="mdi-table-search"
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
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on">
                </v-text-field>
              </template>
              <v-date-picker
                  v-model="startDate"
                  :max="endDate"
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
                    prepend-icon="mdi-calendar"
                    readonly
                    :disabled="inputDisabled"
                    v-bind="attrs"
                    v-on="on">
                </v-text-field>
              </template>
              <v-date-picker
                  v-model="endDate"
                  :min='startDate'
                  no-title
                  @input="menu2 = false">
              </v-date-picker>
            </v-menu>
            <Errors :errors="errors.endDate" />
          </v-col>
          <v-col
              cols="4"
              lg="4">
            <v-file-input
                v-model="importFile"
                type="file"
                ref="importFile"
                label="File input"
                id="input-file-import"
                name="fileImport"
                @change="onFileChange"
                outlined
                dense
            ></v-file-input>
            <v-btn class="import-btn" depressed @click="proceedImport">
              Import
            </v-btn>
<!--            <v-progress-linear :value="uploadPercentage"></v-progress-linear>-->
            <Errors :errors="errors.importFile" />
          </v-col>
        </v-row>
        <div>
          <div>
            <v-btn class="export-btn" depressed @click="getExport">
              Export
            </v-btn>
          </div>
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
          <th><v-checkbox v-model="selectPage" /></th>
          <th data-field="title" class="text-center col-9" @click.prevent="sortBy('title')">
            Title
            <span class="arrow" v-if="sort.direction === 'desc' && sort.field === 'title'">&uarr;</span>
            <span class="arrow" v-if="sort.direction === 'asc' && sort.field === 'title'">&darr;</span>
          </th>
          <th data-field="created_at" class="text-center" @click.prevent="sortBy('created_at')">
            Created At
            <span class="arrow" v-if="sort.direction === 'desc' && sort.field === 'created_at'">&uarr;</span>
            <span class="arrow" v-if="sort.direction === 'asc' && sort.field === 'created_at'">&darr;</span>
          </th>
          <th data-field="action" class="text-center">
            Actions
          </th>
        </tr>
        </thead>
        <tbody v-if="posts.data && posts.data.length > 0">
        <tr v-for="post in posts.data" :key="post.id" :class="isChecked(post.id) ? 'table-primary' : ''">
          <td>
            <v-checkbox :value="post.id" v-model="checked" />
          </td>
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
      </template>
    </v-simple-table>
    <div class="text-center">
      <v-pagination v-model="pagination.current" :total-visible="8" :length="pagination.total" @input="onPageChange" circle></v-pagination>
    </div>
  </div>
</template>

<script>
import {debounce} from 'lodash'
import Errors from '../../../components/Errors'
import PostService from '../../../service/PostService'

export default {
  components: {
    Errors
  },
  data() {
    return {
      posts: [],
      errors: [],
      keyword: this.$route.query.search ?? null,
      pagination: {
        current: this.$route.query.page ?? 1,
        total: 0,
      },
      sort: {
        direction: 'asc',
        field: null,
      },
      startDate: this.$route.query.startDate ?? null,
      endDate: this.$route.query.endDate ?? null,
      menu1: false,
      menu2: false,
      checked: [],
      selectPage: false,
      selectAll: false,
      importFile: null,
      uploadPercentage: 0
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
      return this.startDate === null
    }
  },
  watch: {
    keyword: debounce(function () {
      this.pagination.current = 1
      this.getPosts(this.keyword)
    }, 300),
    startDate: debounce(function () {
      this.getPosts(this.startDate)
    }, 300),
    endDate: debounce(function () {
      this.pagination.current = 1
      this.getPosts(this.endDate)
    }, 300),
    selectPage: function (value) {
      this.checked = [];
      if (value) {
        this.posts.data.forEach(post => {
          this.checked.push(post.id);
        });
      } else {
        this.checked = [];
        this.selectAll = false;
      }
    },
    onUploadProgress: function( progressEvent ) {
      this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ) );
    }.bind(this)
  },
  methods: {
    getPosts() {
      let page = this.pagination.current ?? 1
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
        this.$router.push(params)
        this.errors.endDate = null
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors
        }
      })
    },
    getExport() {
      let checkedPost = this.checked
      let params = `?id=${checkedPost}`

      PostService.export(params).then(response => {
        const fileUrl = window.URL.createObjectURL(response.data)
        const fileLink = document.createElement('a')

        fileLink.href = fileUrl
        fileLink.setAttribute('download', 'export.csv')
        document.body.appendChild(fileLink)
        fileLink.click()
        document.body.removeChild(fileLink)
      }).catch(error => {
        this.errors = error.response.data.errors
      })
    },
    onFileChange(file) {
      this.importFile = file
    },
    proceedImport() {
      let formData = new FormData()
      formData.append('importFile', this.importFile)

      PostService.import(formData).then(response => {
        if (response.status === 200) {
          //
        }
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors
        }
      })
    },
    isChecked(post_id) {
      return this.checked.includes(post_id)
    },
    onPageChange() {
      this.getPosts()
    },
    deletePost(id) {
      if (confirm('Deleted post?')) {
        PostService.delete(id).then(() => {
          this.getPosts()
        });
      }
    },
    sortBy(field) {
      if (this.sort.field === field) {
        this.sort.direction = this.sort.direction === "asc" ? "desc" : "asc"
      } else {
        this.sort.field = field
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
  display: revert;
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

.comment-btn {
  margin-top: 5px;
}

.import-btn {
  margin-bottom: 10px;
}

.v-file-input {
  .v-input__control {
    width: 200px;
  }

}

</style>
