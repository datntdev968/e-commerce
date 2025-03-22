export default [
  {
    path: 'roles',
    name: 'roles',
    component: () => import('@/views/role/IndexView.vue'),
  },
  {
    path: 'roles/:id?/save',
    name: 'roles.save',
    component: () => import('@/views/role/SaveView.vue'),
    props: true,
  },
]
