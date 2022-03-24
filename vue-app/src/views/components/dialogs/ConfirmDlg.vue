<template>
  <v-row justify="center">
    <v-dialog
        v-model="dialog"
        persistent
        :max-width="options.width">
      <v-card>
        <v-card-title class="text-h5 justify-center">
          {{ title }}
        </v-card-title>
        <v-card-text
            v-show="!!message"
            class="pa-4"
            v-html="message">
        </v-card-text>
        <v-card-actions class="dialog-btn-wrapper">
          <v-spacer></v-spacer>
          <v-btn
              v-if="!options.noConfirm"
              color="red darken-1"
              text
              @click.native="cancel">
            Cancel
          </v-btn>
          <v-btn
              color="green darken-1"
              text
              @click.native="agree">
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  name: "ConfirmDlg",
  data() {
    return {
      dialog: false,
      message: null,
      title: null,
      options: {
        width: 300,
        noConfirm: false,
      },
    };
  },
  methods: {
    open(title, message, options) {
      this.dialog = true;
      this.title = title;
      this.message = message;
      this.options = Object.assign(this.options, options);
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    agree() {
      this.resolve(true);
      this.dialog = false;
    },
    cancel() {
      this.resolve(false);
      this.dialog = false;
    },
  },
};
</script>

<style lang="scss">

.dialog-btn-wrapper {
  display: flex;
}

</style>
