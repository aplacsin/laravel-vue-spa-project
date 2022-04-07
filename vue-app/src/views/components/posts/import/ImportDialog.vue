<template>
  <div>
    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
            class="import-btn" depressed
            v-bind="attrs"
            v-on="on"
            @click.stop="dialog = true"
        >
          <v-icon class="mdi mdi-tray-arrow-down"></v-icon>
        </v-btn>
      </template>
      <span>Import</span>
    </v-tooltip>
    <v-dialog
        v-model="dialog"
        max-width="290"
    >
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
              type="file"
              ref="importFile"
              label="File input"
              id="input-file-import"
              name="fileImport"
              outlined
              dense
              v-model="importFile"
              @change="onFileChange"
          ></v-file-input>
          <v-btn
              depressed
              @click="processImport"
              @click.stop="dialog = false"
          >
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



