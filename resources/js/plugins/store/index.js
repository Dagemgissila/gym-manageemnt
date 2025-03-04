import { createStore } from 'vuex';
import auth from './auth';
const store= createStore({
modules:{
    auth
}
});

export { store };
export default function (app) {
  app.use(store)
}


