import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  state: {
    token: localStorage.getItem("token"),
    user: localStorage.getItem('user'),
    userId: localStorage.getItem('id'),
    isUserLoggedIn: localStorage.getItem('isUserLoggedIn'),
  },

  mutations: {
    setToken(state, token) {
      state.token = token;
      if (token) {
        state.isUserLoggedIn = true;
      } else state.isUserLoggedIn = false;
    },
    setUser(state, user) {
      state.user = user;
    },
    setUserId(state, userId) {
        state.userId = userId;
      },
  },
  actions: {
    setToken({ commit }, token) {
      commit("setToken", token);
    },
    setUser({ commit }, user) {
      commit("setUser", user);
    },
    setUserId({ commit }, userId) {
        commit("setUserId", userId);
      },
  },
});
