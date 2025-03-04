import axiosAdmin from "@/composables/axios/axiosAdmin";
import { router } from '@/plugins/1.router';

const AUTH_USER="gms_user";
const AUTH_TOKEN='gms_token';
const EXIPRES_KEY='gms_expire_key'


const getJSONFromLocalStorage=(key)=>{
    const value=window.localStorage.getItem(key)

    if (value==='undefined' || value ==='null' || value===undefined || value === null){
        return null
    }
    else{
        return JSON.parse(value)
    }
}


export default {
    namespaced:true,
    state(){
        return{
            user: getJSONFromLocalStorage(AUTH_USER),
            token: window.localStorage.getItem(AUTH_TOKEN) || null,
            expires: window.localStorage.getItem(EXIPRES_KEY) || null,
        }
    },
    mutations:{
        updateUser(state,user){
            state.user=user;
            window.localStorage.setItem(AUTH_USER, JSON.stringify(user));
        },
        updateToken(state, token) {
            state.token = token;
            window.localStorage.setItem(AUTH_TOKEN, token);

            // Setting up auth bearer token to axios
            axiosAdmin.defaults.headers.common["Authorization"] = `Bearer ${token}`
        },

        updateExpires(state, expires) {
            state.expires = new Date(expires);
            window.localStorage.setItem(EXIPRES_KEY, expires);
        },
    },
        actions: {
            async logout(context) {
              try {
                await axiosAdmin.post("/logout"); // Wait for logout API call
          
                // Clear stored data
                window.sessionStorage.clear();
                window.localStorage.clear();
          
                // Reset Vuex store
                context.commit("updateUser", []);
                context.commit("updateToken", null);
                context.commit("updateExpires", null);
          
                // Redirect to login page
                await router.push("/login"); 
              } catch (error) {
                console.error("Logout failed:", error);
              }
            }
          ,
        updateUser(context) {
            axiosAdmin.get(`/user`)
                .then(function (response) {
                    context.commit('updateUser', response.user);
                })
                .catch(function (error) {

                });
        },
        refreshToken(context) {
            const token = context.state.token;

            if (token !== "" && token !== "undefined" && token != 'null' && token != null) {
                axiosAdmin
                    .post(`/refresh-token`)
                    .then(function (response) {
                        context.commit("updateUser", response.user);
                        context.commit("updateToken", response.token);
                        context.commit("updateExpires", response.expires_in);
                    })
                    .catch(function (error) { });
            }
        },
    },
    getters: {
        isLoggedIn: (state) => {
            return !!state.token && state.token !== 'null' && state.token !== 'undefined';
        }
    }
    
}
