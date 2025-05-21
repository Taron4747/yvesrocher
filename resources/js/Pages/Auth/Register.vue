<!-- resources/js/Pages/Auth/Register.vue -->
<template>
    <div class="max-w-md mx-auto mt-10">
      <h2 class="text-2xl font-bold mb-4">Register</h2>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label>Name</label>
          <input v-model="form.name" type="text" class="input" />
          <div v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</div>
        </div>
  
        <div class="mb-4">
          <label>Email</label>
          <input v-model="form.email" type="email" class="input" />
          <div v-if="form.errors.email" class="text-red-600">{{ form.errors.email }}</div>
        </div>
  
        <div class="mb-4">
          <label>Password</label>
          <input v-model="form.password" type="password" class="input" />
          <div v-if="form.errors.password" class="text-red-600">{{ form.errors.password }}</div>
        </div>
  
        <div class="mb-4">
          <label>Confirm Password</label>
          <input v-model="form.password_confirmation" type="password" class="input" />
        </div>
  
        <button :disabled="form.processing" class="btn">Register</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3'
  
  const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  })
  
  function submit() {
    form.post('/register')
  }
  </script>
  
  <style scoped>
  .input {
    border: 1px solid #ccc;
    padding: 0.5rem;
    width: 100%;
  }
  .btn {
    background: #4f46e5;
    color: white;
    padding: 0.5rem 1rem;
  }
  </style>
  