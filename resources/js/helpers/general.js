export function initialize(store, router) {
    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const currentUser = store.state.currentUser;


        const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);
        const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
        const previousNearestWithMeta = from.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

        if (nearestWithTitle) document.title = nearestWithTitle.meta.title;
        Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));

        if (!nearestWithMeta) {
            if (requiresAuth && !currentUser) {
                next('/login');
            }
            else if (to.path == '/login' && currentUser) {
                next('/');
            } else {
                next();
            }
        }
        else {
            nearestWithMeta.meta.metaTags.map(tagDef => {
                const tag = document.createElement('meta');

                Object.keys(tagDef).forEach(key => {
                    tag.setAttribute(key, tagDef[key]);
                });
                tag.setAttribute('data-vue-router-controlled', '');

                return tag;
            })
                .forEach(tag => document.head.appendChild(tag));

            if (requiresAuth && !currentUser) {
                next('/login');
            } else if (to.path == '/login' && currentUser) {
                next('/');
            } else {
                next();
            }
        }

    });

    //axios.interceptors.response.use(null, (error) => {
    //  if (error.response.status == 401) {
    //       store.commit('logout');
    //      router.push('/login');
    //   }

    //  return Promise.reject(error);
    //});

    if (store.getters.currentUser) {
        setAuthorization(store.getters.currentUser.token);
    }

}
import Echo from 'laravel-echo'
let csrf_token = document.head.querySelector('meta[name="csrf-token"]');
export function setAuthorization(token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
        host: location.host,
        csrfToken: csrf_token.content,
        auth: {
            headers: {

                Accept: 'application/json',
                Authorization: `Bearer ${token}`
            }
        }
    });
}
