<script setup>

</script>

<template>
  <div class="login-page">
    <div class="login-card">
      <div class="login-header">
        <div class="icon-circle">
          <img src="../assets/icones/logo-login.png" alt="User Icon" class="user-icon">
        </div>
        <h1>LOGIN</h1>
      </div>

      <form action="" method="post" @submit.prevent class="login-form">
        <div class="input-group">
          <input type="text" placeholder="Username" required />
        </div>

        <div class="input-group">
          <input type="password" placeholder="Password" required />
        </div>

        <button type="submit" class="btn-submit">LOGIN</button>
      </form>

    </div>
  </div>
</template>

<style>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 3%;
}

.login-card {
  background-color: white;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 380px;
  text-align: center;
}

.icon-circle {
  width: 90px;
  height: 90px;
  border: 2px solid #ced4da;
  border-radius: 50%;
  background-color: #c8c6c6;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto 15px;
}

.user-icon {
  width: 55%;
  height: auto;
}

h1 {
  color: #495057;
  letter-spacing: 4px;
  font-weight: 400;
  margin-bottom: 30px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.input-group {
  display: flex;
  align-items: center;
  border: 1.5px solid #ced4da;
  border-radius: 6px;
  padding: 10px 12px;
}

input {
  border: none;
  outline: none;
  width: 100%;
  font-size: 1rem;
}

.btn-submit {
  background-color: #4fc3f7; 
  color: white;
  border: none;
  padding: 14px;
  border-radius: 6px;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
  margin-top: 10px;
  transition: background 0.3s;
}

.btn-submit:hover {
  background-color: #29b6f6;
}

.login-footer {
  margin-top: 25px;
  font-size: 0.85rem;
  color: #6c757d;
}

.login-footer a {
  color: #6c757d;
  text-decoration: underline;
}
</style>