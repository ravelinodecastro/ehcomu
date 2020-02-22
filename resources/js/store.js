import { getLocalUser } from "./helpers/auth";
const user = getLocalUser();

export default {
    state: {
        currentUser: user,
    },
    getters: {
        currentUser(state) {
            return state.currentUser;
        },
 
    },
    mutations: {
        loginSuccess(state, payload) {
            state.currentUser = Object.assign({}, payload.user, { token: payload.token });
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        logout(state) {
            localStorage.removeItem("user");
            state.currentUser = null;
        },
        updateUser(state, payload) {
            state.currentUser = Object.assign({}, payload.data, { token: state.currentUser.token });
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },

    },
    actions: {
        fetchUser(context) {
            axios.get('/user/profile')
                .then((response) => {
                    if (response.data.success)
                        context.commit('updateUser', response.data);
                })
        },
    }
};