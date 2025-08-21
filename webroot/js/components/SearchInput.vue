<template>
  <div>
    <input
      v-model="query"
      @input="onSearch"
      type="text"
      placeholder="Buscar..."
      class="form-control"
    />
    <ul v-if="results.length">
      <li v-for="item in results" :key="item.id" @click="selectItem(item)">
        {{ item.nome }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  apiUrl: { type: String, required: true }
})
const emit = defineEmits(['selected'])

const query = ref('')
const results = ref([])

const onSearch = async () => {
  if (query.value.length < 2) {
    results.value = []
    return
  }
  try {
    const response = await axios.get(props.apiUrl, {
      params: { q: query.value }
    })
    results.value = response.data.data || []
  } catch (err) {
    console.error(err)
  }
}

const selectItem = (item) => {
  emit('selected', item)
  query.value = item.nome
  results.value = []
}
</script>

<style scoped>
ul {
  list-style: none;
  padding: 0;
  margin: 5px 0 0 0;
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid #ccc;
  border-radius: 4px;
}
li {
  padding: 5px 10px;
  cursor: pointer;
}
li:hover {
  background-color: #f0f0f0;
}
</style>
