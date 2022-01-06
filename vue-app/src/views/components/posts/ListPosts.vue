<template>
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
        <tr v-for="post in posts.data" :key="post.id">
          <td>{{ post.title }}</td>
          <td>{{ new Date(post.created_at).toLocaleString('ru-RU') }}</td>
          <td style="display: table-cell;
            justify-content: center;
            align-items: center;">
            <router-link :to="{ name: 'ShowPost', params: { id: post.id }}"
              style="text-decoration: none; color: inherit; font-size: 20px;" class="button-action flex-column"><span
                class="mdi mdi-eye"></span></router-link>
            <router-link :to="{ name: 'EditPost', params: { id: post.id }}"
              style="text-decoration: none; color: inherit; font-size: 20px;" class="button-action flex-column"><span
                class="mdi mdi-pencil"></span></router-link>
            <button style="color: red; font-size: 20px;" class="button-action flex-column"
              @click="deletePost(post.id)"><span class="mdi mdi-delete"></span></button>
          </td>

        </tr>
      </tbody>
      <div class="text-center">
        <v-pagination v-model="pagination.current" :length="pagination.total" @input="getPosts" circle></v-pagination>
      </div>

    </template>
  </v-simple-table>
</template>

<script>
  import Post from "@/service/Post";
  export default {
    data() {
      return {
        posts: {},
        pagination: {
          current: 1,
          total: 0
        }
      }
    },
    created() {
      this.getPosts()
    },
    methods: {
      getPosts(current) {
        Post.post(current).then(response => {
          this.posts = response.data
          this.pagination.current = response.data.current_page
          this.pagination.total = response.data.last_page
        }).catch(e => {
          console.log(e)
        })
      },
      deletePost(id) {
        if (confirm("Are you sure to delete this category ?")) {
          Post.delete(id).then(() => {
            let i = this.posts.data.map(data => data.id).indexOf(id)
            this.posts.data.splice(i, 1)
            console.log('Delete Success')
          });
        }
      }
    }
  }
</script>

<style scoped lang="scss">

</style>