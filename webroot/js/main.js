import { createApp } from 'vue'
import SearchInput from './components/SearchInput.vue'

const app = createApp({})
app.component('search-input', SearchInput)
app.mount('#app') // div raiz do Vue na página
