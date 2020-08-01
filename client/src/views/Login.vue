<template>
  <v-app>
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" sm="8">
            <v-card class="elevation-12">
              <v-window v-model="step">
                <v-window-item :value="1">
                  <v-row>
                    <v-col cols="12" md="8">
                      <v-card-text class="mt-5">
                        <h1 class="text-center display-1 green--text text">
                          Login To My Posts App
                        </h1>
                        <v-form
                          @submit.prevent="loginUser"
                          id="check-login-form"
                          class="mt-5"
                        >
                          <v-text-field
                            v-model="login.email"
                            :rules="emailRules"
                            label="E-mail"
                            required
                            prepend-icon="email"
                            type="text"
                            color="green"
                          />
                          <v-text-field
                            v-model="login.password"
                            id="password"
                            label="Password"
                            name="Password"
                            prepend-icon="lock"
                            type="password"
                            color="green"
                            required
                          />
                        </v-form>
                      </v-card-text>
                      <div class="text-center mb-3">
                        <v-btn
                          form="check-login-form"
                          type="submit"
                          rounded
                          color="green"
                          dark
                          >Login</v-btn
                        >
                      </div>
                    </v-col>
                    <v-col cols="12" md="4" class="green">
                      <v-card-text class="white--text mt-12">
                        <h1 class="text-center display-1">Hello, friends</h1>
                        <h4 class="text-center">
                          Enter your personal details and start sharing your
                          wonderful posts
                        </h4>
                      </v-card-text>
                      <div class="text-center">
                        <v-btn rounded outlined="" dark @click="step++"
                          >Register</v-btn
                        >
                      </div>
                    </v-col>
                  </v-row>
                </v-window-item>
                <v-window-item :value="2">
                  <v-row class="fill-height">
                    <v-col cols="12" md="4" class="green">
                      <v-card-text class="white-texxt mt-12">
                        <h1 class="text-center display-1">
                          Welcome Back!
                        </h1>
                        <h4 class="text-center">
                          To keep connected with others' posts please login with
                          your personal info
                        </h4>
                      </v-card-text>
                      <div class="text-center">
                        <v-btn rounded outlined="" dark @click="step--"
                          >Login</v-btn
                        >
                      </div>
                    </v-col>
                    <v-col cols="12" md="8">
                      <v-card-text class="mt-12">
                        <h1 class="text-center display-1 green--text">
                          Create An Account
                        </h1>

                        <v-form
                          ref="form"
                          v-model="valid"
                          lazy-validation
                          @submit.prevent="registerUser"
                          id="check-register-form"
                        >
                          <v-text-field
                            v-model="register.name"
                            :counter="10"
                            :rules="nameRules"
                            label="Name"
                            prepend-icon="person"
                            type="text"
                            color="green"
                            required
                          />
                          <v-text-field
                            v-model="register.email"
                            :rules="emailRules"
                            label="E-mail"
                            required
                            prepend-icon="email"
                            type="text"
                            color="green"
                          />
                          <v-text-field
                            v-model="register.password"
                            id="password"
                            label="Password"
                            name="Password"
                            prepend-icon="lock"
                            type="password"
                            required
                            color="green"
                            :rules="[rules.password]"
                          />
                        </v-form>
                      </v-card-text>
                      <div class="text-center mt-n5">
                        <v-btn
                          type="submit"
                          form="check-register-form"
                          rounded
                          color="green"
                          dark
                          >Register</v-btn
                        >
                      </div>
                    </v-col>
                  </v-row>
                </v-window-item>
              </v-window>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      rules: {
        required: (value) => !!value || "Required.",
        password: (value) => {
          const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
          return (
            pattern.test(value) ||
            "Min. 8 characters with at least one capital letter, a number and a special character."
          );
        },
      },
      step: 1,
      register: {
        name: "",
        email: "",
        password: "",
      },
      login: {
        email: "",
        password: "",
      },
      valid: true,
      nameRules: [
        (v) => !!v || "Name is required",
        (v) => (v && v.length <= 10) || "Name must be less than 10 characters",
      ],
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ],
    };
  },
  methods: {
    navigateTo(route) {
      this.$router.push(route);
    },
    registerUser() {
      console.log(this.register.password);
      if (
        this.register.password.match(
          /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/
        ) &&
        this.register.email.match(
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        )
      ) {
        axios
          .post("http://127.0.0.1:8000/api/register", this.register)
          .then((response) => {
            this.$store.dispatch("setToken", response.data.access_token);
            this.$store.dispatch("setUser", response.data.user.name);
            localStorage.setItem("token", response.data.access_token);
            localStorage.setItem('id',response.data.user.id)
            this.navigateTo({ name: "posts" });
            console.log(response);
          })
          .catch((error) => {
            if (error.response.status == 500) {
              alert("email or name already exists");
            }
          });
      } else alert("Please check your credentials");
    },

    loginUser() {
      axios
        .post("http://127.0.0.1:8000/api/login", this.login)
        .then((response) => {
          this.$store.dispatch("setToken", response.data.access_token);
          this.$store.dispatch("setUser", response.data.user.name);
          if (!localStorage.getItem("token")) {
            localStorage.setItem("token", response.data.access_token);
            localStorage.setItem('id',response.data.user.id)

          }
          this.navigateTo({ name: "posts" });

          console.log(response);
        })
        .catch((error) => {
          if (error.response.status == 500) {
            alert("Something wrong happened");
            return 0;
          } else if (error.response.status == 404) {
            alert("Email or Password is incorrect");
            return 0;
          }
          console.log(error.response);
        });
    },
  },

  props: {
    source: String,
  },
};
</script>
