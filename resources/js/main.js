
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

import "@babel/polyfill";

import Vue from 'vue'
import App from './App'

import { store } from './store'
import { router } from './routes'

router.beforeEach((to, from, next) => {

	let allowNext = true;
	const layoutRecord = to.matched.find(record => record.meta.layout);

	// If layout meta exist set it.
	if (layoutRecord !== undefined) {
		store.commit('setLayout', layoutRecord.meta.layout);
	} else {
		store.commit('setLayout');
	}

	// Check if page requires guest or authenticated user.
	if (to.matched.some(record => record.meta.requireGuest)) {
		if (store.getters.isUserAuthenticated) {
			allowNext = false;
		}
	} else if (to.matched.some(record => record.meta.requireAuth)) {
		if (!store.getters.isUserAuthenticated) {
			allowNext = false;
		}
	}

	if (allowNext) {
		next();
	} else {
		next('/');
	}
})

new Vue({
	el: '#app',
	store: store,
	router: router,
	render: h => h(App)
})
