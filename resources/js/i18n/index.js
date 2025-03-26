import { createI18n } from 'vue-i18n';
import en from './en.json';
import it from './it.json';

const messages = {
  en,
  it
};

const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem('locale') || 'en',
  fallbackLocale: 'en',
  messages
});

export default i18n;