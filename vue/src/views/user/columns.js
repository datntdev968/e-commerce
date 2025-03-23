const columns = [
  {
    title: 'CODE',
    dataIndex: 'uni_code',
    key: 'uni_code',
    sorter: (a, b) => a.uni_code.localeCompare(b.uni_code),
    width: '5%',
  },
  {
    title: 'THÔNG TIN KHÁCH HÀNG',
    key: 'name',
    dataIndex: 'name',
    width: '20%',
  },
  {
    title: 'NGÀY SINH',
    dataIndex: 'birthday',
    key: 'birthday',
    width: '13%',
  },
  {
    title: 'ĐỊA CHỈ',
    dataIndex: 'address',
    key: 'address',
  },
  {
    title: 'GIỚI TÍNH',
    dataIndex: 'gender',
    key: 'gender',
    width: '8%',
  },
  {
    title: 'NGÀY TẠO',
    dataIndex: 'created_at',
    key: 'created_at',
  },
  {
    title: 'TRẠNG THÁI',
    dataIndex: 'published',
    key: 'published',
    width: '10%',
  },
]

export { columns }
