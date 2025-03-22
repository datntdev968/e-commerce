<template>
	<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
		<div class="text-center">
			<div class="mb-4">
				<i class="bi bi-shield-lock" style="font-size: 6rem; color: #dc3545"></i>
			</div>
			<h1 class="display-3 text-danger">403 Forbidden</h1>
			<p class="lead text-muted">Bạn không có quyền truy cập vào trang này.</p>
			<p>
				<router-link :to="{ name: 'dashboard' }" class="btn btn-primary">
					Quay lại trang chủ
				</router-link>
			</p>
		</div>
	</div>
</template>

<script setup>
</script>

<style scoped>
/* Sử dụng flexbox để căn giữa nội dung */
.min-vh-100 {
	min-height: 100vh;
}

/* Định dạng lại icon lỗi */
.bi-shield-lock {
	color: #dc3545;
}
</style>
