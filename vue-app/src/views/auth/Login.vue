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
                <v-text-field
                    type="email"
                    label="Email"
                    prepend-icon="mdi-email"
                    class="form-control"
                    id="email"
                    v-model="form.email"
                ></v-text-field>
                <Errors :errors="errors.email"/>
                <v-text-field
                    type="password"
                    label="Password"
                    prepend-icon="mdi-lock"
                    class="form-control"
                    id="password"
                    v-model="form.password"
                ></v-text-field>
                <Errors :errors="errors.password"/>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                  color="primary"
                  @click.prevent="login"
              >Login
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-main>
</template>

<script>


import Errors from "../../components/Errors";
import AuthUserService from "../../service/UserService";
import User from "@/service/UserService";

export default {
  components: {
    Errors
  },
  data() {
    return {
      form: {
        email: null,
        password: null,
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
            User.auth().then(response => {
              const user = response.data
              localStorage.setItem('userData', JSON.stringify(user));
            });
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
