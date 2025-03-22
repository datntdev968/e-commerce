<template>
	<div>
		<label v-if="props.label" :for="props.name" :class="props.labelClass">{{ props.label }} </label>
		<a-tree-select
			v-model:value="value"
			show-search
			style="width: 100%"
			:dropdown-style="{ maxHeight: '400px', overflow: 'auto' }"
			:placeholder="props.placeholder"
			autoClearSearchValue
			allow-clear
			:mode="props.mode"
			tree-default-expand-all
			:show-checked-strategy="SHOW_ALL"
			:tree-data="props.treeData"
			:field-names="{
				children: 'children',
				label: 'name',
				value: 'value',
			}"
			tree-node-filter-prop="label"
		>
		</a-tree-select>
		<small v-if="errorMessage" class="text-danger fw-bold">{{ errorMessage }}</small>
	</div>
</template>
<script setup>
import { useField } from 'vee-validate'
import { TreeSelect } from 'ant-design-vue'
const SHOW_ALL = TreeSelect.SHOW_ALL

const props = defineProps({
	treeData: {
		type: [Array, Object],
		default: null,
	},
	name: {
		type: String,
		required: true,
	},
	oldValue: {
		type: [String, Boolean, Number],
		default: '',
	},
	placeholder: {
		type: String,
		default: '',
	},
	labelClass: {
		type: String,
		default: 'fw-bold mb-2',
	},
	label: {
		type: String,
		default: '',
	},
	mode: {
		type: String,
		default: 'default',
	},
})

const { value, errorMessage } = useField(props.name)
</script>
