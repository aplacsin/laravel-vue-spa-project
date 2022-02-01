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
      <div class="create-comment">
        <div class="comment-title-text">Leave a comment</div>
        <v-divider></v-divider>
        <template>
          <v-container fluid>
            <v-textarea clearable clear-icon="mdi-close-circle" label="Your comment"
                        v-model="comments.content"></v-textarea>
          </v-container>
          <input hidden v-model="post.id" />
          <v-alert v-if="message" dense text type="success">{{ message }}</v-alert>
          <Errors :errors="errors.content" />
          <v-btn class="comment-btn" depressed @click="storeComment()">
            Add comment
          </v-btn>
        </template>
        <br><br>
        <v-divider></v-divider>
        <div class="comment-title-text">Display comment</div>
        <FetchComments :comments="post.comments" />
      </div>
    </v-card>
  </div>
</template>

<script>
import FetchComments from '../comments/FetchComments.vue';
import Errors from "../../../components/Errors";
import PostService from "../../../service/PostService";
import CommentService from "../../../service/CommentService";

export default {
  components: {
    FetchComments,
    Errors
  },
  data() {
    return {
      post: [],
      message: null,
      errors: [],
      comments: []
    }
  },
  mounted() {
    this.getPost(this.$route.params.id)
  },
  methods: {
    getPost(id) {
      PostService.show(id).then(response => {
        this.post = response.data.data
      });
    },
    storeComment() {
      const data = {
        id: this.post.id,
        type: 'post',
        content: this.comments.content,
      };
      CommentService.store(data).then(response => {
        console.log(response.data)
        this.message = 'The comment was stored successfully!'
        this.comments.content = ''
        this.getPost(this.$route.params.id)
      }).catch(e => {
        console.log(e)
        if (e.response.status === 422) {
          this.errors = e.response.data.errors
        }
      });
    },
      hasHistory() {
          return window.history.length > 2
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

.comment-title-text {
  font-size: 25px;
  margin-left: 10px;
}

.comment-btn {
  margin-left: 10px;
}

.comment-btn v-btn {
  margin-bottom: 10px;
}
</style>
