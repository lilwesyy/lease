<template>
    <div>
      <header>
        <h1>Benvenuto nella mia applicazione!</h1>
        <nav>
          <router-link to="/">Home</router-link>
          <router-link to="/login">Login</router-link>
        </nav>
      </header>
    </div>
  </template>

  <script>
  export default {
    name: 'App'
  }
  </script>
