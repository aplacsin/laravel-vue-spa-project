<template>
  <div>
    <v-btn class="import-btn" depressed
           @click.stop="dialog = true">
      <span class="mdi mdi-import"></span>
    </v-btn>
    <v-dialog
        v-model="dialog"
        max-width="290">
      <v-card>
        <v-card-title class="text-h5 justify-center">
          Import
        </v-card-title>
        <v-card-text>
          Upload csv file!
        </v-card-text>
        <v-card-actions class="import-btn-wrapper">
          <v-spacer></v-spacer>
          <v-file-input
              v-model="importFile"
              type="file"
              ref="importFile"
              label="File input"
              id="input-file-import"
              name="fileImport"
              @change="onFileChange"
              outlined
              dense
          ></v-file-input>
          <v-btn depressed @click="processImport" @click.stop="dialog = false">
            Import
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    processImport: {
      type: Function
    },
    onFileChange: {
      type: Function
    }
  },
  data() {
    return {
      dialog: false,
      importFile: this.importFile
    }
  },
}
</script>

<style scoped lang="scss">

.import-btn {
  margin-right: 5px !important;
  height: 36px !important;
  min-width: 0 !important;
  padding: 0 16px !important;
  width: 36px !important;

  .mdi-import {
    font-size: 20px;
    transform: rotate(90deg);
  }
}

.v-file-input {
  .v-input__control {
    width: 150px;
  }
}

.import-btn-wrapper {
  display: grid;
}

</style>



