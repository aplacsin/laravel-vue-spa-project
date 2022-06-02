<template>
  <div>
    <v-btn
        class="comment-btn"
        @click.exact="dialog = true"
        @click="getEditComment(comments)"
        text
    >Edit
    </v-btn>
    <v-dialog
        v-model="dialog"
        max-width="500"
    >
      <v-card>
        <v-card-title class="text-h5 justify-center">
          Edit Comment
        </v-card-title>
        <div class="wrapper-content">
          <v-textarea
              v-model="comment.content"
              outlined
              clearable
              clear-icon="mdi-close-circle"
          />
        </div>
        <v-card-actions class="import-btn-wrapper">
          <v-spacer></v-spacer>
          <v-btn
              color="red darken-1"
              text
              @click.stop="dialog = false"
          >Cancel
          </v-btn>
          <v-btn
              text
              @click="editComment(comments.id)"
              @click.stop="dialog = false"
          >Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import CommentService from "@/service/CommentService";

export default {
  props: {
    comments: [],
    getComment: {
      type: Function
    },
  },
  data() {
    return {
      comment: [],
      dialog: false,
    }
  },
  methods: {
    getEditComment(comment) {
      this.comment = Object.assign({}, comment);
    },
    editComment(id) {
      const data = {
        content: this.comment.content
      };
      CommentService.update(id, data).then(response => {
        response.data;
        this.getComment(this.$route.params.id);
        this.message = 'The comment was updated success!';
        this.$toast.success(this.message);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors.content[0]);
        }
      });
    }
  }
}
</script>

<style scoped lang="scss">

.btn-edit {
  text-decoration: none;
  color: inherit;
}

.comment-btn {
  font-size: 12px;
  margin-top: 15px;
  margin-bottom: 5px;
  margin-left: 15px;
}

.wrapper-content {
  margin-right: 15px;
  margin-left: 15px;
}

</style>
