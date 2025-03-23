// import axiosInstance from '@/configs/axios'
import { ref, reactive } from 'vue'
import useAction from '@/composables/useAction'

const { getAll } = useAction()

// Custom hook để quản lý bảng
export default function usePagination(apiUrl) {
  const resData = ref([])
  const loading = ref(false)
  const ids = ref([])

  const pagination = reactive({
    current: 1,
    pageSize: 10,
    total: 0,
    pageSizeOptions: ['10', '20', '50', '100'], // Các tùy chọn kích thước trang
    showSizeChanger: true,
  })

  let oldParam = reactive({
    // filterCustom: null,
    // sortField: null,
    // sortOrder: null,
    // deleted_at: null,
    // searchField: null,
    // searchText: null,
  })

  // Hàm fetch data từ server
  const fetchData = async (params = {}, resetFilter = false) => {
    loading.value = true

    if (resetFilter) {
      oldParam = {}
    } else {
      oldParam = Object.assign({}, oldParam, {
        page: params.current || pagination.current,
        pageSize: params.pageSize || pagination.pageSize,
        sortField: params.sortField ?? oldParam.sortField, // Giữ sortField nếu không có mới
        sortOrder: params.sortOrder ?? oldParam.sortOrder, // Giữ sortOrder nếu không có mới
        searchText: params.search ?? oldParam.searchText, // Giữ searchText nếu không có mới
        searchField: Array.isArray(params.searchField)
          ? params.searchField
          : params.searchField
            ? [params.searchField]
            : oldParam.searchField, // Giữ searchField nếu không có mới
        filterCustom: params.filter ?? oldParam.filterCustom, // Giữ filterCustom nếu không có mới
        deleted_at: params.deleted_at ?? oldParam.deleted_at, // Giữ deleted_at nếu không có mới
      })
    }

    try {
      const response = await getAll(apiUrl, oldParam)

      resData.value = response.data.data

      pagination.current = response.data.current_page
      pagination.pageSize = response.data.per_page
      pagination.total = response.data.total
    } catch (error) {
      console.error('Error fetching data:', error)
    } finally {
      loading.value = false
    }

    // oldParam = param
  }

  // Hàm xử lý khi bảng thay đổi (phân trang, sắp xếp)
  const handleTableChange = (paginationInfo, filters, sorter) => {
    fetchData({
      current: paginationInfo.current,
      pageSize: paginationInfo.pageSize,
      sortField: sorter.field,
      sortOrder: sorter.order === 'ascend' ? 'asc' : 'desc',

      // searchText: oldParam.searchText,
      // searchField: Array.isArray(oldParam.searchField)
      //   ? oldParam.searchField
      //   : oldParam.searchField
      //     ? [oldParam.searchField]
      //     : null,
      // filterCustom: oldParam.filterCustom || null,
      // deleted_at: oldParam.deleted_at || null,
    })
  }

  const onSelectChange = (selectedRowKeys) => {
    ids.value = selectedRowKeys
  }

  const resetSelectedRowKeys = () => {
    ids.value = []
  }

  const rowSelection = {
    onChange: (selectedRowKeys) => {
      ids.value = selectedRowKeys
    },
    getCheckboxProps: (record) => ({
      disabled: record.name === 'Disabled User', // Column configuration not to be checked
      name: record.name,
    }),
  }

  return {
    resData,
    loading,
    pagination,
    rowSelection,
    ids,
    fetchData,
    handleTableChange,
    onSelectChange,
    resetSelectedRowKeys,
  }
}
