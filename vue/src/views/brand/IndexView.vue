<template>
	<MasterComponent>
		<template #template>
			<PageHeaderComponent :title="state.title" :name="state.createName" />

			<ToolboxComponent
				@search="onSearch"
				:ids="ids"
				:model="state.modelName"
				@fetch="fetchData"
				@resetChecked="resetSelectedRowKeys"
				:updateTitle="updateTitle"
			/>

			<div class="card">
				<div class="card-body">
					<a-table
						:columns="columns"
						:data-source="resData"
						:loading="loading"
						:pagination="pagination"
						@change="handleTableChange"
						:row-selection="{ selectedRowKeys: ids, onChange: onSelectChange }"
					>
						<template #bodyCell="{ column, record }">
							<template v-if="column.dataIndex === 'name'">
								<router-link :to="{ name: 'brands.update', params: { id: record.key } }">
									{{ record.name }}
								</router-link>
							</template>

							<template v-if="column.dataIndex === 'website_url'">
								<a :href="record.website_url" target="_bank">{{ record.website_url }}</a>
							</template>

							<template v-if="column.dataIndex === 'published'">
								<StatusSwitchComponent :record="record" :modelName="state.modelName" />
							</template>
						</template>
					</a-table>
				</div>
			</div>
		</template>
	</MasterComponent>
</template>

<script setup>
import MasterComponent from '@/components/layouts/MasterComponent.vue'

import { PageHeaderComponent, ToolboxComponent, StatusSwitchComponent } from '@/components/includes'

import usePagination from '@/composables/usePagination'

import { removeLastTwoWords } from '@/utils/helpers'

import { columns } from './columns'

import { onMounted, reactive } from 'vue'

const {
	resData,
	loading,
	pagination,
	handleTableChange,
	fetchData,
	ids,
	onSelectChange,
	resetSelectedRowKeys,
} = usePagination('/brands')

const state = reactive({
	title: 'Danh sách thương hiệu',
	createName: 'brands.create',
	modelName: 'Brand',
})

onMounted(() => {
	fetchData()
})

const updateTitle = (bool) => {
	state.title = bool == true ? state.title + ' lưu trữ' : removeLastTwoWords(state.title)
}

const onSearch = (searchText, filter, deleted_at) => {
	fetchData({
		current: pagination.current,
		pageSize: pagination.pageSize,
		search: searchText,
		filter: filter,
		deleted_at,
	})
}
</script>

<style lang="scss" scoped>
</style>
