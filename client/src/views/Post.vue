<template>
  <v-data-table
    v-if="$store.state.token || $store.state.isUserLoggedIn"
    :headers="headers"
    :items="posts"
    :loading="loading"
    :search="search"
    :items-per-page="10"
    :sort-desc.sync="sortDesc"
    :sort-by.sync="sortBy"
    item-key="items.key"
    class="elevation-3 row"
    :footer-props="{
      showFirstLastPage: true,
      firstIcon: 'mdi-arrow-collapse-left',
      lastIcon: 'mdi-arrow-collapse-right',
      prevIcon: 'mdi-minus',
      nextIcon: 'mdi-plus',
    }"
  >
    <template v-slot:item.id="{ item }">
      <v-chip :color="getColor()" @click="navigateTo(item.id)">{{
        item.id
      }}</v-chip>
    </template>
    <template v-slot:item.user.name="{ item }">
      <span @click="navigateTo(item.id)">{{ item.user.name }}</span>
    </template>
    <template v-slot:item.title="{ item }">
      <span @click="navigateTo(item.id)">{{ item.title }}</span>
    </template>
    <template v-slot:item.body="{ item }">
      <span @click="navigateTo(item.id)">{{ item.body }}</span>
    </template>
    <template v-slot:item.created_at="{ item }">
      <span @click="navigateTo(item.id)">{{
        new Date(item.created_at).toLocaleString()
      }}</span>
    </template>

    <template v-slot:top>
      <v-toolbar text color="white">
        <v-toolbar-title>Posts</v-toolbar-title>
        <v-text-field
          class="ml-6"
          v-model="search"
          append-icon="search"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="green" dark v-bind="attrs" v-on="on">New Post</v-btn>
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
      <v-icon
        v-if="$store.state.userId == item.user_id"
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        v-if="$store.state.userId == item.user_id"
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
    </template>
  </v-data-table>
</template>

<style scoped>
.row {
  cursor: pointer;
}
</style>

<script>
import axios from "axios";
export default {
  data: () => ({
    search: "",
    dialog: false,
    loading: true,
    sortBy: "created_at",
    sortDesc: true,
    headers: [
      {
        text: "ID",
        aligh: "start",
        sortable: false,
        value: "id",
      },
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
    navigateTo(id) {
      this.$router.push({
        name: "details",
        params: {
          myProperty: id,
        },
      });
    },
    getColor() {
      return "red";
    },
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
      if (this.$store.state.userId == item.user_id) {
        this.editedIndex = this.posts.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true;
      } else {
        alert("Unauthorized User");
        return 0;
      }
    },

    deleteItem(item) {
      if (this.$store.state.userId == item.user_id) {
        try {
          let token = localStorage.getItem("token");

          confirm("Are you sure you want to delete this post?") &&
            axios
              .delete(`http://127.0.0.1:8000/api/post/${item.id}`, {
                headers: {
                  Authorization: `Bearer ` + token,
                  "Content-Type": "application/json",
                },
              })
              .then((response) => {
                if (response.data.message === "wrong user") {
                  alert("Unauthorized User");
                  return 0;
                }
                let arr = this.posts;
                const result = arr.filter((post) => post.id !== item.id);
                this.posts = result;
              });
        } catch (err) {
          console.log(err);
        }
      } else {
        alert("Unauthorized User");
        return 0;
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
        if (this.$store.state.userId == this.editedItem.user_id) {
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
                alert("Unauthorized User");
              }
            });
        } else {
          this.close();
          alert("Unauthorized User");
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
