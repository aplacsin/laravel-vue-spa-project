<template>
  <div>
    <router-link :to="{ name: 'ListVideos' }">
      <v-btn depressed color="primary" class="link-btn back-btn">
        Back
      </v-btn>
    </router-link>
    <v-card v-if="video">
      <v-responsive>
        <v-card-text>
          <h2><b>{{ video.title }}</b></h2><br><br>
          <div class="wrapper-video">
            <iframe
                :src="`https://player.vimeo.com/video/${video.video_id}?h=791afcfe42&amp;title=0&amp;byline=0&amp;portrait=0&amp;speed=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=234456`"
                max-width="100%" width="720" height="480" frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                :title="`${video.title}`">
            </iframe>
          </div>
        </v-card-text>
      </v-responsive>
      <v-divider></v-divider>
      <div class="create-comment">
        <div class="comment-title-text">Leave a comment</div>
        <v-divider></v-divider>
        <template>
          <v-container fluid>
            <v-textarea clearable clear-icon="mdi-close-circle" label="Your comment"
                        v-model="comments.content">
            </v-textarea>
          </v-container>
          <input hidden v-model="video.id"/>
          <input hidden v-model="video.type"/>
          <v-alert v-if="message" dense text type="success">{{ message }}</v-alert>
          <Errors :errors="errors.content" />
          <v-btn class="comment-btn" depressed @click="storeComment()">
            Add comment
          </v-btn>
        </template>
        <br><br>
        <v-divider></v-divider>
        <div class="comment-title-text">Display comment</div>
        <FetchComments :comments="video.comments"/>
      </div>
    </v-card>
  </div>
</template>

<script>
import FetchComments from '../comments/FetchComments.vue';
import CommentService from "@/service/CommentService";
import VideoService from "@/service/VideoService";
import Errors from "@/views/Errors";

export default {
  components: {
    FetchComments,
    Errors
  },
  data() {
    return {
      video: [],
      message: null,
      errors: [],
      comments: [],
    }
  },
  mounted() {
    this.getVideo(this.$route.params.id)
  },
  methods: {
    getVideo(id) {
      VideoService.show(id).then(response => {
        this.video = response.data.data
      });
    },
    storeComment() {
      const data = {
        id: this.video.id,
        type: this.video.type,
        content: this.comments.content,
      };
      CommentService.store(data).then(response => {
        console.log(response.data)
        this.message = 'The comment was stored successfully!'
        this.comments.content = ''
        this.getVideo(this.$route.params.id)
      }).catch(e => {
        console.log(e);
        if (e.response.status === 422) {
          this.errors = e.response.data.errors
        }
      });
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

.wrapper-video {
  display: flex;
  justify-content: center;
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