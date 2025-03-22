<template>
	<MasterComponent>
		<template #template>
			<PageHeaderComponent :title="state.title" />

			<form class="mt-3" @submit.prevent="onSubmit">
				<div class="card">
					<div class="card-body">
						<div class="row align-items-center">
							<div class="col-lg-9">
								<InputComponent
									label="Tên vai trò"
									name="name"
									type="text"
									placeholder="Tên vai trò"
									required="true"
									classCustom="mb-2"
								/>
							</div>
							<div class="col-lg-3">
								<a-button html-type="submit" class="mt-3" :loading="state.loading" type="primary">
									<i class="bi bi-floppy2 me-2"></i>Lưu thay đổi
								</a-button>
							</div>
						</div>
					</div>
				</div>

				<div class="card mt-3">
					<div class="card-body">
						<div v-if="Object.keys(permissions).length">
							<div v-for="(perms, group) in permissions" :key="group" class="card mb-3 shadow-sm">
								<div
									class="card-header bg-dark text-white d-flex justify-content-between align-items-center"
								>
									<!-- Tiêu đề nhóm quyền -->
									<div>
										<strong class="me-2">{{
											group.charAt(0).toUpperCase() + group.slice(1)
										}}</strong>
										<span class="badge bg-light text-dark">{{ perms.length }} quyền</span>
									</div>

									<!-- Nút "Check All" nằm bên phải tiêu đề -->
									<div class="d-flex align-items-center">
										<a-checkbox
											:checked="isAllSelected(group)"
											@change="toggleAllPermissions(group, $event)"
											class="text-light"
										>
											Chọn tất cả
										</a-checkbox>
									</div>
								</div>
								<div class="card-body d-flex flex-column gap-3 align-items-start">
									<!-- Xếp các checkbox trong hàng ngang -->
									<div class="d-flex flex-wrap gap-3">
										<div
											v-for="perm in perms"
											:key="perm.id"
											class="list-group-item d-flex align-items-center"
										>
											<a-checkbox
												:checked="selectedPermissions.includes(perm.name)"
												@change="togglePermission(perm.name, $event)"
											>
												{{ perm.name }}
												<!-- {{ perm.name.split(" ").slice(1).join(" ") }} -->
											</a-checkbox>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div v-else class="alert alert-warning">Không có dữ liệu quyền nào.</div>
					</div>
				</div>
			</form>
		</template>
	</MasterComponent>
</template>

<script setup>
import MasterComponent from '@/components/layouts/MasterComponent.vue'
import { PageHeaderComponent, InputComponent } from '@/components/includes'
import useAction from '@/composables/useAction'
import { useForm } from 'vee-validate'
import { useRoute, useRouter } from 'vue-router'
import * as Yup from 'yup'

import { reactive, ref, onMounted, watch, computed } from 'vue'
import { message } from 'ant-design-vue'

const { getAll, create, update, getOne, data } = useAction()

const route = useRoute()

const router = useRouter()

const permissions = ref({})

const state = reactive({
	title: 'Thêm mới vai trò',
	apiCreate: '/roles',
	loading: false,
})

const selectedPermissions = ref([])

const id = computed(() => route.params.id || null)

// Fetch dữ liệu quyền
const fetchPermissions = async () => {
	const response = await getAll('get-permissions')
	permissions.value = response.data
}

const schema = Yup.object({
	name: Yup.string().required('Tên vai trò không được để trống!'),
})

const { handleSubmit, setValues } = useForm({
	validationSchema: schema,
})

const fetchOne = async () => {
	const response = await getOne(state.apiCreate, id.value)

	if (response.data && response.data.permissions) {
		// Chuyển danh sách quyền từ chuỗi sang mảng
		selectedPermissions.value = response.data.permissions.split(', ').map((perm) => perm.trim())
	}

	const { name } = data.value.data

	setValues({ name }) // Set giá trị cho form từ data lấy được
}

onMounted(() => {
	fetchPermissions()

	if (id.value) {
		fetchOne()
	}
})

// Kiểm tra xem tất cả quyền trong nhóm đã được chọn chưa
const isAllSelected = (group) => {
	const groupPermissions = permissions.value[group] || []
	return (
		groupPermissions.length > 0 &&
		groupPermissions.every((perm) => selectedPermissions.value.includes(perm.name))
	)
}

// Chọn hoặc bỏ chọn tất cả quyền trong nhóm
const toggleAllPermissions = (group, event) => {
	const groupPermissions = permissions.value[group] || []
	if (event.target.checked) {
		groupPermissions.forEach((perm) => {
			if (!selectedPermissions.value.includes(perm.name)) {
				selectedPermissions.value.push(perm.name)
			}
		})
	} else {
		selectedPermissions.value = selectedPermissions.value.filter(
			(perm) => !groupPermissions.some((gp) => gp.name === perm)
		)
	}
}

// Toggle một quyền riêng biệt
const togglePermission = (permName, event) => {
	if (event.target.checked) {
		if (!selectedPermissions.value.includes(permName)) {
			selectedPermissions.value.push(permName)
		}
	} else {
		selectedPermissions.value = selectedPermissions.value.filter((perm) => perm !== permName)
	}
}

const onSubmit = handleSubmit(async (values) => {
	const payload = {
		...values,
		permissions: [...selectedPermissions.value],
	}

	try {
		state.loading = true
		const response = id.value
			? await update(state.apiCreate, id.value, payload)
			: await create(state.apiCreate, payload)

		if (!response.success) {
			return message.error(getFirstError(error.value.errors))
		}

		message.success(response.message)

		router.go(-1)
	} catch (error) {
		console.log(error)
	} finally {
		state.loading = false
	}
})
</script>

<style scoped>
</style>
