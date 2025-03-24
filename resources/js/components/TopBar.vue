<template>
  <div class="topbar">
    <div class="menu">
      <div class="avatar-container">
        <span class="username">{{ username }}</span>
        <Avatar
          icon="pi pi-user"
          class="mr-2 avatar-hover"
          size="large"
          style="background-color: #ece9fc; color: #2a1261"
          shape="circle"
          @click="toggleMenu"
        />
        <span class="notification-badge">{{ notificationCount }}</span>
      </div>
      <Menu :model="items" ref="menu" popup />
    </div>
  </div>
</template>

<script>
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Menu from 'primevue/menu';

export default {
  name: 'TopBar',
  components: {
    Button,
    Avatar,
    Menu
  },
  data() {
    return {
      items: [
        { label: 'Profile', icon: 'pi pi-user', command: () => { /* handle profile click */ } },
        { label: 'Settings', icon: 'pi pi-cog', command: () => { /* handle settings click */ } },
        { label: 'Logout', icon: 'pi pi-sign-out', command: () => { /* handle logout click */ } }
      ],
      notificationCount: 5, // Example notification count
      username: 'John Doe' // Example username
    };
  },
  methods: {
    toggleMenu(event) {
      this.$refs.menu.toggle(event);
    }
  }
};
</script>

<style scoped>
.topbar {
  margin: 0!important;
  display: flex;
  align-items: center;
  padding: 10px 20px;
}

.logo {
  height: 40px;
}

.menu {
  display: flex;
  gap: 10px;
  margin-left: auto;
}

.avatar-container {
  position: relative;
  display: flex;
  align-items: center;
}

.avatar-hover {
  transition: transform 0.3s ease;
  cursor: pointer;
}

.avatar-hover:hover {
  transform: scale(1.1);
}

.username {
  margin-right: 10px;
  font-size: 16px;
  color: #2a1261;
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background-color: red;
  color: white;
  border-radius: 50%;
  padding: 5px 10px;
  font-size: 12px;
}
</style>