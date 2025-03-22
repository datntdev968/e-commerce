<template>
	<masterComponent>
		<template #template>
			<PageHeaderComponent :title="state.title" />

			<div class="row mt-3">
				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">
							<form action="" @submit.prevent="onSubmit">
								<InputComponent
									label="Tên quyền"
									name="name"
									placeholder="Nhập tên quyền"
									required="true"
									classCustom="mb-3"
									tooltipText="ví dụ: Product View"
								/>

								<a-button html-type="submit" :loading="state.loading" class="mt-3" type="primary"
									><i class="bi bi-floppy2 me-2"></i>Lưu thay đổi
								</a-button>

								<a-button html-type="button" class="mt-3 ms-2" @click="resetFormData">
									<i class="bi bi-arrow-clockwise me-2"></i>Đặt lại
								</a-button>

								<div class="mt-3 text-muted">
									<small>
										Quy tắc đặt tên quyền:
										<ul>
											<li>View: Xem danh sách</li>
											<li>ViewAny: Xem chi tiết</li>
											<li>Store: Thêm mới</li>
											<li>Update: Cập nhật</li>
											<li>Delete: Xóa</li>
										</ul>
										Tên quyền bắt đầu bằng một từ miêu tả chức năng, theo sau là hành động (ví
										dụ: Product View).
									</small>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">
							<a-table
								:columns="columns"
								:data-source="resData"
								:loading="loading"
								:pagination="pagination"
								@change="handleTableChange"
								:row-selection="{ selectedRowKeys: ids, onChange: onSelectChange }"
								:row-class-name="rowClassName"
							>
								<template #bodyCell="{ column, record }">
									<div
										v-if="column.dataIndex === 'name'"
										@click="handleRowClick(record)"
										class="pointer"
									>
										{{ record.name }}
									</div>

									<div v-else-if="column.dataIndex === 'published'">
										<StatusSwitchComponent :record="record" :modelName="state.modelName" />
									</div>
								</template>
							</a-table>
						</div>
					</div>
				</div>
			</div>
		</template>
	</masterComponent>
</template>

<script setup>
import MasterComponent from '@/components/layouts/MasterComponent.vue'
import { PageHeaderComponent, InputComponent } from '@/components/includes'
import usePagination from '@/composables/usePagination'
import * as Yup from 'yup'
import { useForm } from 'vee-validate'
import { message } from 'ant-design-vue'
import useAction from '@/composables/useAction'
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import { getFirstError } from '@/utils/helpers'

const { create, error, update } = useAction()

const route = useRoute()

const selectedRowKey = ref(null)

const columns = [
	{
		title: 'TÊN VAI TRÒ',
		dataIndex: 'name',
		key: 'name',
		sorter: (a, b) => a.name.length - b.name.length,
	},
	{
		title: 'NGÀY TẠO',
		dataIndex: 'created_at',
		key: 'created_at',
	},
]

const {
	resData,
	loading,
	pagination,
	handleTableChange,
	fetchData,
	ids,
	onSelectChange,
	resetSelectedRowKeys,
} = usePagination('/permissions')

const state = reactive({
	title: 'Danh sách quyền hạn',
	modelName: 'Permission',
	apiCreate: '/permissions',
})

onMounted(() => {
	fetchData()
})

const schema = Yup.object({
	name: Yup.string().required('Tên quyền không được để trống!'),
})

const { handleSubmit, resetForm } = useForm({
	validationSchema: schema,
})

const id = computed(() => route.params.id || null)

const onSubmit = handleSubmit(async (values) => {
	try {
		const response = selectedRowKey.value
			? await update(state.apiCreate, selectedRowKey.value, values)
			: await create(state.apiCreate, values)

		if (!response.success) {
			return message.error(getFirstError(error.value.errors))
		}

		message.success(response.message)
		resetFormData()
		fetchData()
	} catch (error) {
		console.log(error)
	} finally {
		state.loading = false
	}
})

const handleRowClick = (record) => {
	resetForm({
		values: {
			name: record.name,
		},
	})

	selectedRowKey.value = record.key
}

const rowClassName = (record) => {
	return record.key === selectedRowKey.value ? 'disabled-row' : ''
}

const resetFormData = () => {
	resetForm({
		values: {
			name: '',
		},
	})

	selectedRowKey.value = null
}
</script>

<style  scoped>
</style>
