import { computed } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex/dist/vuex.cjs.js";

const common = () => {
    const route = useRoute();
    const store = useStore();
    const user = computed(() => store.state.auth.user);

    const permsArray = computed(() => {
        const permsArrayList = user && user.value && user.value.role ? [user.value.role.name] : [];


        if (user && user.value && user.value.role) {
            // Use the forEach method directly on the array
            user.value.role.permissions.forEach((permission) => {
                permsArrayList.push(permission.name);
            });
        }

        return permsArrayList;
    });

    return {
        user,
        permsArray
    };
};

export default common;
