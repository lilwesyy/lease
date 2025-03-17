import { createStore } from 'vuex';
import axios from 'axios';

const store = createStore({
  state: {
    user: null,
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
  },
  actions: {
    async fetchUser({ commit }) {
      try {
        const response = await axios.get('/api/user', { withCredentials: true });
        commit('setUser', response.data);
      } catch (error) {
        console.error('Error fetching user data', error);
      }
    },
  },
  getters: {
    user: (state) => state.user,
  },
});

export default store;
