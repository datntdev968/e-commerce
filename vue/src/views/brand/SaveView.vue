<template>
	<MasterComponent>
		<template #template>
			<PageHeaderComponent :title="state.title" />

			<form class="mt-3" @submit.prevent="onSubmit">
				<div class="row">
					<div class="col-lg-9">
						<a-tabs v-model:activeKey="activeKey" type="card">
							<a-tab-pane key="1" tab="Thông tin thương hiệu">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-lg-2">
												<InputFinderComponent name="logo" label="Logo" />
											</div>

											<div class="col-lg-10">
												<div class="row">
													<div class="col-lg-6">
														<InputComponent
															label="Tên thương hiệu"
															name="name"
															type="text"
															placeholder="Nhập tên thương hiệu"
															required="true"
															classCustom="mb-2"
														/>
													</div>

													<div class="col-lg-6">
														<InputComponent
															label="Slug"
															name="slug"
															type="text"
															placeholder="Nhập slug"
															tooltipText="Không nhập sẽ tự động lấy theo tên"
														/>
													</div>

													<div class="col-lg-12">
														<SelectComponent
															label="Tags"
															name="seo_tags"
															type="text"
															mode="tags"
															placeholder="Nhập Tags"
														/>
													</div>
												</div>
											</div>

											<div class="col-lg-12">
												<EditorComponent name="description" label="Mô tả thương hiệu" />
											</div>
										</div>
									</div>
								</div>
							</a-tab-pane>

							<!-- Tab 2: SEO thương hiệu -->
							<a-tab-pane key="2" tab="SEO thương hiệu">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-lg-12">
												<InputComponent
													label="Tiêu đề SEO"
													name="seo_title"
													type="text"
													placeholder="Nhập tiêu đề SEO"
													classCustom="mb-2"
												/>

												<InputComponent
													label="Mô tả SEO"
													name="seo_description"
													typeInput="textarea"
													placeholder="Nhập mô tả SEO"
													classCustom="mb-2"
													rows="4"
													maxlength="150"
												/>

												<SelectComponent
													label="Từ khóa SEO"
													name="seo_keywords"
													type="text"
													mode="tags"
													placeholder="Nhập từ khóa SEO"
												/>
											</div>
										</div>
									</div>
								</div>
							</a-tab-pane>
						</a-tabs>
					</div>

					<div class="col-lg-3 mt-4-5">
						<div class="card mb-3">
							<div class="card-body">
								<SelectComponent
									name="published"
									label="Trạng thái"
									:options="PUBLISH"
									:oldValue="1"
								/>
							</div>
						</div>
						<div class="card mb-3">
							<div class="card-body">
								<InputComponent
									label="Địa chỉ trang web"
									name="website_url"
									type="text"
									placeholder="Nhập địa chỉ trang web"
								/>
							</div>
						</div>

						<a-button html-type="submit" class="mt-3" :loading="state.loading" type="primary"
							><i class="bi bi-floppy2 me-2"></i>Lưu thay đổi
						</a-button>
					</div>
				</div>
			</form>
		</template>
	</MasterComponent>
</template>

<script setup>
import MasterComponent from '@/components/layouts/MasterComponent.vue'
import {
	PageHeaderComponent,
	InputComponent,
	InputFinderComponent,
	SelectComponent,
	EditorComponent,
} from '@/components/includes'
import { reactive, computed, onMounted, ref } from 'vue'
import * as Yup from 'yup'
import { useForm } from 'vee-validate'
import { message } from 'ant-design-vue'
import useAction from '@/composables/useAction'
import { useRouter, useRoute } from 'vue-router'
import { getFirstError } from '@/utils/helpers'
import { PUBLISH } from '@/static/constants'

const state = reactive({
	title: 'Thêm mới thương hiệu',
	apiCreate: '/brands',
	loading: false,
})

const activeKey = ref('1') // Mặc định là tab "Thông tin thương hiệu"

const { getOne, create, error, data, update } = useAction()

const router = useRouter()
const route = useRoute()

const id = computed(() => route.params.id || null)

const schema = Yup.object({
	name: Yup.string().required('Tên không được để trống!'),
})

const { handleSubmit, setValues } = useForm({
	validationSchema: schema,
})

const onSubmit = handleSubmit(async (values) => {
	try {
		state.loading = true
		const response = id.value
			? await update(state.apiCreate, id.value, values)
			: await create(state.apiCreate, values)

		console.log(response)

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

const fetchOne = async () => {
	await getOne(state.apiCreate, id.value)

	const {
		name,
		slug,
		logo,
		website_url,
		description,
		seo_title,
		seo_description,
		seo_keywords,
		seo_tags,
		published,
	} = data.value.data

	setValues({
		name,
		slug,
		logo,
		website_url,
    description,
		seo_title,
		seo_description,
		seo_keywords: seo_keywords ?? [],
		seo_tags: seo_tags ?? [],
		published,
	})
}

onMounted(async () => {
	if (id.value) fetchOne()
})
</script>

<style lang="scss" scoped>
</style>
