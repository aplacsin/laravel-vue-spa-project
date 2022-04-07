<template>
  <div class="reply-comment" v-if="comments && comments.length > 0">
    <div>
      <ConfirmDlg ref="confirm"/>
    </div>
    <div v-for="comment in comments" :key="comment.id">
      <v-divider></v-divider>
      <div class="wrapper-comment">
        <div class="wrapper-comment-avatar">
          <div class="user-avatar">
            <img :src="image" alt="download.jpeg"/>
          </div>
        </div>
        <div class="wrapper-content">
          <div class="user-name-comment">
            <div><b>{{ comment.user.name }}</b> - {{ comment.created_at }}</div>
          </div>
          <div class="comment-content">
            <div> {{ comment.content }}</div>
          </div>
          <div class="d-flex">
            <div class="reply">
              <v-btn
                  class="comment-btn"
                  text
                  @click="isShow = !isShow"
              >Reply
              </v-btn>
            </div>
            <div>
            </div>
            <div v-if="user.id === comment.user_id">
              <EditComment
                  :comments="comment.id"
                  :getComment="getComment"
              />
            </div>
            <div v-if="user">
              <v-btn
                  class="comment-btn"
                  color="red darken-1"
                  text
                  v-if="user.id === comment.user_id"
                  @click="delComment(comment.id)"
              >Delete
              </v-btn>
            </div>
          </div>
        </div>
      </div>
      <div v-show="isShow">
        <v-container fluid>
          <v-textarea
              clearable
              clear-icon="mdi-close-circle"
              label="Your comment"
              v-model="content"
          ></v-textarea>
        </v-container>
        <v-btn
            class="comment-btn"
            depressed
            @click="storeReplyComment(comment.id, content)"
        >Send
        </v-btn>
      </div>
      <ReplyComments
          :comment="comment"
          :getComment="getComment"
          :deleteComment="delComment"
          :storeComment="storeReplyComment"
      />
    </div>
  </div>
  <div v-else>
    <div class="text-center">
      No comments found.
    </div>
  </div>
</template>

<script>
import CommentService from "@/service/CommentService";
import ConfirmDlg from "@/views/components/dialogs/ConfirmDlg";
import EditComment from "@/views/components/comments/EditComment";
import ReplyComments from "@/views/components/comments/ReplyComments";

export default {
  components: {
    ReplyComments,
    EditComment,
    ConfirmDlg
  },
  props: {
    comments: [],
    getComment: {
      type: Function
    },
    id: [],
    type: [] ?? '',
  },
  data() {
    return {
      user: [],
      isShow: false,
      content: [],
      image: '/img/download.jpeg'
    }
  },
  mounted() {
    this.user = JSON.parse(window.localStorage.getItem('userData'))
  },
  methods: {
    async delComment(id) {
      if (
          await this.$refs.confirm.open(
              "Confirm",
              "Are you sure you want to delete this comment?"
          )
      ) {
        this.deleteComment(id);
      }
    },
    deleteComment(id) {
      CommentService.delete(id).then(response => {
        response.data;
        this.getComment(this.$route.params.id);
        this.message = 'The comment was deleted success!';
        this.$toast.success(this.message);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors);
        }
      });
    },
    storeReplyComment(parentId, content) {
      const data = {
        id: this.id,
        type: this.type,
        content: content,
        parentId: parentId ?? null
      };
      CommentService.store(data).then(response => {
        response.data;
        this.content = '';
        this.getComment(this.$route.params.id);
        this.message = 'The comment was stored success!';
        this.$toast.success(this.message);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors.content[0]);
        }
      });
    },
  }
}
</script>

<style scoped lang="scss">

.wrapper-comment {
  display: flex;
}

.wrapper-comment-avatar {
  max-width: 80px;
  max-height: 80px;
  margin-top: 10px;
  margin-left: 10px;
}

.user-avatar img {
  width: 80px;
  height: 80px;

  .user-avatar img {
    width: 100%;
  }
}

.user-name-comment {
  margin-top: 5px;
  margin-left: 15px;
  margin-right: 15px;
}

.comment-content {
  margin-top: 10px;
  margin-left: 15px;
  margin-right: 15px;
  white-space: -moz-pre-wrap !important;
  white-space: -o-pre-wrap;
  white-space: pre-wrap;
  word-wrap: break-word;
  word-break: break-all;
}

.comment-btn {
  font-size: 12px;
  margin-top: 15px;
  margin-bottom: 5px;
  margin-left: 15px;
}

</style>
