import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './components/Home'
import Broadcast from './components/Broadcast'
import Sermons from './components/Sermons'
import Sermon from './components/Sermon'
import Schedule from './components/Schedule'
import ContactUs from './components/ContactUs'
import HostDashboard from './components/HostDashboard'
import AccountLogin from './components/AccountLogin'
import AccountRegister from './components/AccountRegister'
import AccountPasswordReset from './components/AccountPasswordReset'
import AccountPasswordChange from './components/AccountPasswordChange'
import AccountEdit from './components/AccountEdit'
import Admin from './components/Admin'
import NotFound from './components/404'

Vue.use(VueRouter)

export const router = new VueRouter({
	mode: 'history',
	routes: [
		{
			path: '/',
			name: 'home',
			component: Home
		},
		{
			path: '/broadcasts/:broadcast_id',
			name: 'broadcast',
			component: Broadcast,
			meta: { layout: 'no-header-layout' }
		},
		{
			path: '/sermons',
			name: 'sermons',
			component: Sermons
		},
		{ 
			path: '/sermons/:sermon_id',
			name: 'sermon',
			component: Sermon,
			meta: { layout: 'no-header-layout' }
		},
		{
			path: '/schedule',
			name: 'schedule',
			component: Schedule 
		},
		{
			path: '/contact',
			name: 'contact',
			component: ContactUs
		},
		{
			path: '/host',
			name: 'host',
			component: HostDashboard,
			meta: { layout: 'host-layout' }
		},
		{
			path: '/login',
			name: 'login',
			component: AccountLogin,
			meta: {
				requireGuest: true,
				layout: 'no-header-layout'
			}
		},
		{
			path: '/register',
			name: 'register',
			component: AccountRegister,
			meta: {
				requireGuest: true,
				layout: 'no-header-layout'
			}
		},
		{
			path: '/password/reset',
			name: 'password.reset',
			component: AccountPasswordReset,
			meta: {
				requireGuest: true,
				layout: 'no-header-layout'
			}
		},
		{
			path: '/password/reset/:token',
			name: 'password.reset.change',
			component: AccountPasswordChange,
			meta: {
				requireGuest: true,
				layout: 'no-header-layout'
			}
		},
		{
			path: '/user/edit',
			name: 'user.edit',
			component: AccountEdit,
			meta: {
				requireAuth: true
			}
		}, 
		{
			path: '/admin',
			name: 'admin',
			component: Admin
		},
		{
			path: '*',
			redirect: '/'
		}
	] 
})