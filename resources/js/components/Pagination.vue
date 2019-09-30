<template>
  <div class="pagination-block">
    <div class="pagination">
      <router-link :to="{ query: {page: `${currentPage - 1}` }}" v-if="! isFirstPage" append>前ページ</router-link>
      <router-link :to="{ query: {page: `${currentPage + 1}` }}" v-if="! isLastPage" append>次ページ</router-link>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    currentPage: {
      type: Number,
      required: true
    },
    lastPage: {
      type: Number,
      required: true
    }
  },
  computed: {
    isFirstPage() {
      return this.currentPage === 1;
    },
    isLastPage() {
      return this.currentPage === this.lastPage;
    }
  }
};
</script>
