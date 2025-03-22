const columns = [
  {
    title: 'TÊN VAI TRÒ',
    dataIndex: 'name',
    key: 'name',
    sorter: (a, b) => a.name.length - b.name.length,
  },
  // {
  //   title: 'DÀNH CHO',
  //   dataIndex: 'guard_name',
  //   key: 'guard_name',
  //   sorter: (a, b) => a.guard_name.length - b.guard_name.length,
  // },
  {
    title: 'QUYỀN HẠN',
    dataIndex: 'permissions',
    key: 'permissions',
    width: '69%',
  },
  {
    title: 'NGÀY TẠO',
    dataIndex: 'created_at',
    key: 'created_at',
  },
]

export { columns }
