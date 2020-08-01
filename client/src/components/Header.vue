<template>
  <v-toolbar fixed class="green" dark>
    <v-toolbar-title class="mr-4">
      <span class="home" @click="navigateTo({ name: 'home' })">Posty</span>
    </v-toolbar-title>
    <v-spacer></v-spacer>
    <v-toolbar-items>
      <v-btn
        v-if="!$store.state.isUserLoggedIn && !$store.state.token"
        text
        dark
        id="login-home"
        @click="navigateTo({ name: 'login' })"
      >
        Login
      </v-btn>
    </v-toolbar-items>
    <v-toolbar-items>
      <v-btn
        v-if="$store.state.token || $store.state.isUserLoggedIn"
        text
        dark
        id="login-home"
        @click="navigateTo({ name: 'posts' })"
      >
        Posts
      </v-btn>
    </v-toolbar-items>
    <v-toolbar-items>
      <v-btn
        v-if="$store.state.token || $store.state.isUserLoggedIn"
        text
        dark
        id="login-home"
        @click="logout()"
      >
        Logout
      </v-btn>
    </v-toolbar-items>
  </v-toolbar>
</template>

<script>
import axios from "axios";
import store from "../store/store";

export default {
  methods: {
    navigateTo(route) {
      this.$router.push(route);
    },

    logout() {
      console.log(store.state.token);
      axios
        .post("http://127.0.0.1:8000/api/logout", store.state.token, {
          headers: {
            Authorization: `Bearer ` + store.state.token,
            "Content-Type": "application/json",
          },
        })
        .then((response) => {
          localStorage.removeItem("token");
          this.$store.dispatch("setToken", null);
          this.$store.dispatch("setUser", null);
          alert(response.data[1]);
          console.log(response.data);
          this.navigateTo({ name: "home" });
        });
    },
  },
};
</script>

<style scoped>
.home {
  cursor: pointer;
}

.home:hover,
#login-home:hover {
  color: gold;
}

#login-home {
  background-color: #4caf50;
  border: none;
  outline: none;
}

.v-btn--contained {
  box-shadow: unset;
}
</style>
