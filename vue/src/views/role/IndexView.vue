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
								<router-link :to="{ name: 'roles.save', params: { id: record.key } }">
									{{ record.name }}
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
import { columns } from './columns'
import usePagination from '@/composables/usePagination'
import { PageHeaderComponent, InputFinderComponent, EditorComponent } from '@/components/includes'
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
} = usePagination('/roles')

const state = reactive({
	title: 'Danh sách vai trò',
	modelName: 'Role',
	createName: 'roles.save',
})

onMounted(() => {
	fetchData()
})
</script>

<style  scoped>
</style>
