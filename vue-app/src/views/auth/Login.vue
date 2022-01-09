<template>
  <v-main>
    <v-container fluid>
      <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
          <v-card class="elevation-12">
            <v-toolbar dark color="primary">
              <v-toolbar-title>Login form</v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-form>
                <v-text-field type="email" label="Email" prepend-icon="mdi-email" v-model="form.email"
                              class="form-control" id="email"></v-text-field>
                <v-alert dense outlined type="error" v-if="errors.email">
                  {{ errors.email[0] }}
                </v-alert>
                <v-text-field type="password" label="Password" prepend-icon="mdi-lock" v-model="form.password"
                              class="form-control" id="password"></v-text-field>
                <v-alert dense outlined type="error" v-if="errors.password">
                  {{ errors.password[0] }}
                </v-alert>
              </v-form>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click.prevent="login">Login</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-main>
</template>

<script>
import AuthUserService from "@/service/UserService";

export default {
  data() {
    return {
      form: {
        email: "",
        password: "",
        device_name: "browser"
      },
      errors: []
    };
  },
  methods: {
    login() {
      AuthUserService.login(this.form)
          .then(response => {
            this.$root.$emit("login", true);
            localStorage.setItem("token", response.data.token);
            this.$router.push({
              name: "Dashboard"
            });
          })
          .catch(error => {
            if (error.response.status === 422) {
              this.errors = error.response.data.errors;
            }
          });
    }
  }
};
</script>