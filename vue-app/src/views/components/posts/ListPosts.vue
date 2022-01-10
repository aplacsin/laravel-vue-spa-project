<template>
  <div>
    <div>
        <v-container>
          <v-row>
            <v-col
                cols="12"
                md="12"
            >
              <v-text-field
                  v-model="keyword"
                  label="Search..."
              ></v-text-field>
            </v-col>
          </v-row>
        </v-container>
    </div>
  <v-simple-table>
    <template v-slot:default>
      <thead>
      <tr>
        <th class="text-center col-9">
          Title
        </th>
        <th class="text-center">
          Created At
        </th>
        <th class="text-center">
          Actions
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="post in posts" :key="post.id">
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
      <ConfirmDlg ref="confirm"/>
      </tbody>
      <div class="text-center">
        <v-pagination v-model="pagination.current" :length="pagination.total" @input="getPosts" circle></v-pagination>
      </div>
    </template>
  </v-simple-table>
  </div>
</template>

<script>
import PostService from "@/service/PostService";
import ConfirmDlg from "@/views/components/dialogs/ConfirmDlg";
import {debounce} from "lodash";

export default {
  components: {
    ConfirmDlg
  },
  data() {
    return {
      posts: [],
      keyword: null,
      pagination: {
        current: 1,
        total: 0
      }
    }
  },
  created() {
    this.getPosts()
  },
  watch: {
    keyword: debounce(function () {
      this.searchData(this.keyword)
    }, 300),
  },
  methods: {
    getPosts() {
      const page = this.pagination.current ?? 1
      const params = `?page=${page}`

      PostService.list(params).then(response => {
        this.posts = response.data.data
        this.pagination.current = response.data.current_page
        this.pagination.total = response.data.last_page
      }).catch(e => {
        console.log(e)
      })
    },
    searchData(val) {
      if(!val) {
        this.getPosts()
      } else {
        PostService.search(val).then(response => {
          this.posts = response.data
        });
      }
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

</style>