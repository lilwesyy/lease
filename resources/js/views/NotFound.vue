<template>
    <div class="not-found-container">
      <h1>404 - Page Not Found</h1>
      <p>Sorry, the page you are looking for could not be found.</p>
    </div>
  </template>

  <script>
  export default {
    name: 'NotFound'
  };
  </script>

  <style scoped>
  .not-found-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
  }

  .not-found-container h1 {
    font-size: 4rem;
  }

  .not-found-container p {
    font-size: 2rem;
  }
  </style>
