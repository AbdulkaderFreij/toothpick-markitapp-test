<template>
  <div class="home">
    <v-app>
      <v-container>
        <v-row class="text-center">
          <v-col class="mb-4">
            <h1 class="display-1 font-weight-bold mb-3">
              Post Details
            </h1>
          </v-col>
        </v-row>
        <v-card class="mx-auto" max-width="344">
          <v-card-text>
            <div class="font-weight-bold mb-3">Title</div>
            <p class=" text--primary">
              {{ title }}
            </p>
            <p class="font-weight-bold mb-3">Description</p>
            <div class="text--primary">
              {{ body }}
            </div>
          </v-card-text>
        </v-card>
      </v-container>
    </v-app>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data: () => ({
    title: "",
    body: "",
  }),

  mounted() {
    let token = localStorage.getItem("token");
    axios
      .get(`http://127.0.0.1:8000/api/posts/${this.$route.params.myProperty}`, {
        headers: {
          Authorization: `Bearer ` + token,
          "Content-Type": "application/json",
        },
      })
      .then((response) => {
        console.log(response.data[0]);
        this.title = response.data[0].title;
        this.body = response.data[0].body;
      });
  },
};
</script>
