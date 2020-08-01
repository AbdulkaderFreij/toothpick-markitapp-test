<template>
  <v-data-table
    :headers="headers"
    :items="posts"
    :loading="loading"
    class="elevation-3"
  >
    <template v-slot:item.created_at="{ item }">
      <span>{{ new Date(item.created_at).toLocaleString() }}</span>
    </template>

    <template v-slot:top>
      <v-toolbar text color="white">
        <v-toolbar-title>Posts</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="green" dark class="mb-2" v-bind="attrs" v-on="on"
              >New Post</v-btn
            >
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field
                      v-model="editedItem.title"
                      label="Title"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="20" md="20">
                    <v-text-field
                      v-model="editedItem.body"
                      label="Content"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon small class="mr-2" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon small @click="deleteItem(item.id)">
        mdi-delete
      </v-icon>
    </template>
  </v-data-table>
</template>

<script>
import axios from "axios";
export default {
  data: () => ({
    dialog: false,
    loading: true,
    headers: [
      {
        text: "Author",
        align: "start",
        sortable: false,
        value: "user.name",
      },
      {
        text: "Title",
        align: "start",
        sortable: false,
        value: "title",
      },
      {
        text: "Content",
        align: "start",
        sortable: false,
        value: "body",
      },
      {
        text: "Published Date",
        align: "start",
        sortable: false,
        dataType: "Date",
        value: "created_at",
      },
      {
        text: "Actions",
        align: "start",
        sortable: false,
        value: "actions",
      },
    ],
    posts: [],
    editedIndex: -1,
    editedItem: {
      name: "",
      title: "",
      body: "",
      created_at: "",
      user_id: localStorage.getItem("id"),
    },
    defaultItem: {
      name: "",
      title: "",
      body: "",
      created_at: "",
      user_id: localStorage.getItem("id"),
    },
  }),

  mounted() {
    this.getData();
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Post" : "Edit Post";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  methods: {
    getData() {
      let token = localStorage.getItem("token");
      if (token) {
        try {
          axios
            .get("http://127.0.0.1:8000/api/posts", {
              headers: {
                Authorization: `Bearer ` + token,
                "Content-Type": "application/json",
              },
            })
            .then((response) => {
              this.loading = false;
              this.posts = response.data;
            });
        } catch (err) {
          console.log(err);
        }
      } else
        alert(
          "Unauthorized, please login to read, create, edit and delete posts"
        );
    },

    editItem(item) {
      this.editedIndex = this.posts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      try {
        let token = localStorage.getItem("token");

        confirm("Are you sure you want to delete this post?") &&
          axios
            .delete(`http://127.0.0.1:8000/api/post/${item}`, {
              headers: {
                Authorization: `Bearer ` + token,
                "Content-Type": "application/json",
              },
            })
            .then((response) => {
              if (response.data.message === "wrong user") {
                alert("Unauthorizeds");
                return 0;
              }
              let arr = this.posts;
              const result = arr.filter((post) => post.id !== item);
              this.posts = result;
            });
      } catch (err) {
        console.log(err);
      }
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      let token = localStorage.getItem("token");
      if (this.editedIndex > -1) {
        if (this.$store.state.userId == this.editedItem.user.id) {
          Object.assign(this.posts[this.editedIndex], this.editedItem);
          axios
            .put(
              `http://127.0.0.1:8000/api/post/${this.editedItem.id}`,
              {
                title: this.editedItem.title,
                body: this.editedItem.body,
                user_id: this.editedItem.user_id,
              },
              {
                headers: {
                  Authorization: `Bearer ` + token,
                  "Content-Type": "application/json",
                },
              }
            )
            .then((response) => {
              if (response.data.message == "wrong user") {
                alert("Unauthorized");
              }
            });
        } else {
          this.close();
          alert("Anauthorized User");
          return 0;
        }
      } else {
        axios
          .post(
            "http://127.0.0.1:8000/api/post",
            {
              title: this.editedItem.title,
              body: this.editedItem.body,
              user_id: this.editedItem.user_id,
            },
            {
              headers: {
                Authorization: `Bearer ` + token,
                "Content-Type": "application/json",
              },
            }
          )
          .then((response) => {
            this.posts.push(response.data[0]);
          });
      }
      this.close();
    },
  },
};
</script>
