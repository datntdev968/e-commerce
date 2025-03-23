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
							<template v-if="column.key === 'name'">
								<strong>{{ record.name }}</strong> <br />
								<a :href="'mailto:' + record.email">{{ record.email }}</a> <br />
								{{ record.phone }}
							</template>

							<template v-if="column.dataIndex === 'published'">
								<StatusSwitchComponent :record="record" :modelName="state.modelName" />
							</template>

							<template v-if="column.dataIndex === 'uni_code'">
								<router-link :to="{ name: 'users.save', params: { id: record.key } }">
									<b>{{ record.uni_code }}</b>
								</router-link>
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
import {
	PageHeaderComponent,
	InputFinderComponent,
	EditorComponent,
	StatusSwitchComponent,
	ToolboxComponent,
} from '@/components/includes'
import usePagination from '@/composables/usePagination'
import { columns } from './columns'

import { onMounted, reactive } from 'vue'

const state = reactive({
	title: 'Danh sách khách hàng',
	modelName: 'User',
  createName: 'users.save'
})

const {
	resData,
	loading,
	pagination,
	handleTableChange,
	fetchData,
	ids,
	onSelectChange,
	resetSelectedRowKeys,
} = usePagination('/users?user_group_id=3')

onMounted(() => {
	fetchData()
})

const onSearch = (searchText, filter) => {
	fetchData({
		current: pagination.current,
		pageSize: pagination.pageSize,
		search: searchText,
		filter: filter,
		searchField: ['uni_code', 'phone'],
	})
}
</script>

<style scoped>
</style>
