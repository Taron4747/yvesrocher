<!-- resources/js/Pages/Auth/ResetPassword.vue -->
<template>
    <div class="max-w-md mx-auto mt-10">
      <h2 class="text-xl font-bold mb-4">Reset Password</h2>
  
      <form @submit.prevent="submit">
        <input v-model="form.email" type="email" placeholder="Email" class="input" />
        <div v-if="form.errors.email" class="text-red-600">{{ form.errors.email }}</div>
  
        <input v-model="form.password" type="password" placeholder="New Password" class="input mt-2" />
        <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="input mt-2" />
        <div v-if="form.errors.password" class="text-red-600">{{ form.errors.password }}</div>
  
        <button :disabled="form.processing" class="btn mt-4">Reset Password</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3'
  import { usePage } from '@inertiajs/vue3'
  
  const page = usePage()
  const form = useForm({
    token: page.props.token,
    email: page.props.email,
    password: '',
    password_confirmation: ''
  })
  
  function submit() {
    form.post('/reset-password')
  }
  </script>
  