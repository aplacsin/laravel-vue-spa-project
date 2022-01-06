<template>
  <div>
    <router-link :to="{ name: 'ListPosts' }">
      <v-btn depressed color="primary" class="link-btn back-btn">
        Back
      </v-btn>
    </router-link>
    <v-card v-if="post">
      <v-responsive>
        <v-card-text>
          <b>{{ post.title }}</b><br><br>
          {{ domDecoder(post.description) }}
        </v-card-text>
      </v-responsive>
      <v-divider></v-divider>
      <Comments :id="post.id" :type="post.type" />
      <FetchComments :comments="post.comments" />
    </v-card>
  </div>
</template>

<script>
  import Post from "@/service/Post";
  import FetchComments from '../comments/FetchComments.vue';
  import Comments from '../comments/StoreComments.vue'

  export default {
    components: {
      FetchComments,
      Comments
    },
    data() {
      return {
        post: [],
      }
    },
    mounted() {
      this.getPost(this.$route.params.id)
    },
    methods: {
      getPost(id) {
        Post.show(id).then(response => {
          this.post = response.data
        });
      },
      domDecoder(str) {
        let parser = new DOMParser()
        let dom = parser.parseFromString('<!doctype html><body>' + str, 'text/html')
        return dom.body.textContent
      },
    }
  }
</script>

<style scoped lang="scss">
  .link-btn {
    text-decoration: none;
    color: inherit;
  }

  .back-btn {
    margin-bottom: 10px;
  }
</style>