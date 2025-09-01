<template>

    <div class="container py-4">
      <h2 class="mb-4">News</h2>
        <div class="row my-5">
            <!-- search by title -->
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="searchTitle">Search by Title:</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="searchTitle" 
                        v-model="searchTitle" 
                        placeholder="Title" 
                    />
                </div>
            </div>

            <!-- search by date -->
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="searchDate">Search by Date:</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="searchDate" 
                        v-model="searchDate" 
                        placeholder="Date (e.g., 2025-05-01)" 
                    />
                </div>
            </div>

            <!-- search by content -->
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="searchContent">Search by Content:</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="searchContent" 
                        v-model="searchContent" 
                        placeholder="Content" 
                    />
                </div>
            </div>

            <!-- Category filter-->
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label class="form-label">Search by Category:</label>
                    <div class="category-options">
                        <div class="form-check">
                            <input type="radio" id="categoryAll" value="All" v-model="searchCategory">
                            <label for="categoryAll">All</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" id="categoryMenu" value="Menu" v-model="searchCategory">
                            <label for="categoryMenu">Menu</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" id="categoryEvents" value="Events" v-model="searchCategory">
                            <label for="categoryEvents">Events</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" id="categoryPromotion" value="Promotion" v-model="searchCategory">
                            <label for="categoryPromotion">Promotion</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" id="categoryJob" value="Job" v-model="searchCategory">
                            <label for="categoryJob">Job</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" id="categoryAnnouncement" value="Announcement" v-model="searchCategory">
                            <label for="categoryAnnouncement">Announcement</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- news lists -->
        <div v-if="paginatedNews.length">
            <div v-for="item in paginatedNews" :key="item.id" class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title">{{ item.title }}</h3>
                    <h4 class="card-subtitle mb-2 text-muted">{{ item.date }} | {{ item.category }}</h4>
                    <p class="card-text">{{ item.content }}</p>
                </div>
            </div>
        </div>
        <div v-else>
            <p>No news found.</p>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link" @click="currentPage--" :disabled="currentPage === 1">Previous</button>
                </li>
                <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
                    <button class="page-link" @click="changePage(page)">{{ page }}</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link" @click="currentPage++" :disabled="currentPage === totalPages">Next</button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import newsData from '../data/news.json'

export default {
  name: 'News',
  data() {
    return {
      newsItems: newsData,
     
      searchTitle: '',
      searchDate: '',
      searchContent: '',
      searchCategory: '',
      
      currentPage: 1,
      itemsPerPage: 5
    }
  },
  computed: {
     filteredNews() {
      let filtered = this.newsItems
      if (this.searchTitle) {
        filtered = filtered.filter(item => item.title.toLowerCase().includes(this.searchTitle.toLowerCase()))
      }
      if (this.searchDate) {
        filtered = filtered.filter(item => item.date.includes(this.searchDate))
      }
      if (this.searchContent) {
        filtered = filtered.filter(item => item.content.toLowerCase().includes(this.searchContent.toLowerCase()))
      }
      if (this.searchCategory && this.searchCategory !== 'All') {
        filtered = filtered.filter(item => item.category === this.searchCategory)
      }
      return filtered

    },
    // Get paginated news items
    paginatedNews() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      return this.filteredNews.slice(start, start + this.itemsPerPage)
    },
    totalPages() {
      return Math.ceil(this.filteredNews.length / this.itemsPerPage)
    }
  },
  methods: {
    changePage(page) {
      this.currentPage = page
    }
  },
}
</script>

<style scoped>
.card {
  border-left: 5px solid #292a2c;
}

.form-group {
  margin-bottom: 1rem;
}

.category-options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-check {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.pagination {
  margin-top: 1rem;
}
h3, h4{
  font-size: 1.2rem;
}
</style>
