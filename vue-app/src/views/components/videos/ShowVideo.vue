<template>
  <div>
    <v-btn @click="hasHistory()
    ? $router.go(-1)
    : $router.push('/')" depressed color="primary" class="link-btn back-btn">
      Back
    </v-btn>
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
      <Comments
          :id="video.id"
          :type="type"
          :comments="video.comments"
          :content="comments.content"
          :getComment="getVideo"
      />
    </v-card>
  </div>
</template>

<script>
import VideoService from '@/service/VideoService';
import Comments from "@/views/components/comments/Comments";

export default {
  components: {
    Comments
  },
  data() {
    return {
      video: [],
      comments: [],
      type: 'video',
    }
  },
  mounted() {
    this.getVideo(this.$route.params.id);
  },
  methods: {
    getVideo(id) {
      VideoService.show(id).then(response => {
        this.video = response.data.data;
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

.wrapper-video {
  display: flex;
  justify-content: center;
}

</style>
