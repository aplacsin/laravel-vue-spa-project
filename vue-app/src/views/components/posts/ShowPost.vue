<template>
  <div>
    <v-btn @click="hasHistory()
    ? $router.go(-1)
    : $router.push('/')" depressed color="primary" class="link-btn back-btn">
      Back
    </v-btn>
    <v-card v-if="post">
      <v-responsive>
        <v-card-text>
          <b>{{ post.title }}</b><br><br>
          <div v-html="post.description"></div>
        </v-card-text>
      </v-responsive>
      <v-divider></v-divider>
      <Comments :id="post.id" :type="type" :comments="post.comments" :content="comments.content" :getComment="getPost"/>
    </v-card>
  </div>
</template>

<script>
import PostService from "@/service/PostService";
import Comments from "@/views/components/comments/Comments";

export default {
  components: {
    Comments
  },
  data() {
    return {
      post: [],
      comments: [],
      type: 'post',
    }
  },
  mounted() {
    this.getPost(this.$route.params.id);
  },
  methods: {
    getPost(id) {
      PostService.show(id).then(response => {
        this.post = response.data.data;
      });
    },
    hasHistory() {
      return window.history.length > 2;
    }
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
