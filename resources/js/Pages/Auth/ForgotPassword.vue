<!-- resources/js/Pages/Auth/ForgotPassword.vue -->
<template>
    <div class="max-w-md mx-auto mt-10">
      <h2 class="text-xl font-bold mb-4">Forgot Password</h2>
  
      <div v-if="$page.props.flash.status" class="text-green-600 mb-4">
        {{ $page.props.flash.status }}
      </div>
  
      <form @submit.prevent="submit">
        <input v-model="form.email" type="email" placeholder="Email" class="input" />
        <div v-if="form.errors.email" class="text-red-600">{{ form.errors.email }}</div>
  
        <button :disabled="form.processing" class="btn mt-4">Send Reset Link</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3'
  
  const form = useForm({ email: '' })
  
  function submit() {
    form.post('/forgot-password')
  }
  </script>
  