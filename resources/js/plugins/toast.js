export default {
    install(app) {
      app.config.globalProperties.$showToast = (severity, summary, detail, life = 3000) => {
        app.config.globalProperties.$toast.add({ severity, summary, detail, life });
      };
    }
  };