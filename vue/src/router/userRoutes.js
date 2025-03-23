export default [
  {
    path: 'users',
    name: 'users.index',
    component: () => import('@/views/user/IndexView.vue'),
  },
  {
    path: 'users/:id?/save',
    name: 'users.save',
    component: () => import('@/views/user/SaveView.vue'),
  },
]
