<template>
   <div class="categories_content">
      <a
        :href="'/category/' + item.id"
        class="category_item"
        v-for="item in categories.filter(c => c.id !== 1000).slice(0, 4)"
        :key="item.id"
      >
        {{ item[`name_${$page.props.locale}`] }} 
      </a>
    </div>

    <div class="categories_content categories_content_small">
      <div
        class="category_item"
        v-for="item in categoryBanners"
        :class="{ category_item_active: item.type }"
        @click="changeProducts(item.id)"
        :key="item.id"
      >
        <div>{{ item.title }} </div>
      </div>
    </div>
  <div class="carousel_full_wrapper">
    <!-- категории: оставлены без изменений -->
   

    <!-- карусель товаров -->
    <div class="carousel_wrapper">
      <!-- <h2 class="carousel_title">Популярные товары</h2> -->

      <div class="arrow left">
        <button @click="prevSlide">
          <img src="images/arrow_carusel.svg"/>
        </button>
      </div>

      <div class="products_content_wrapper" ref="carouselWrapper">
        <div
          class="products_content"
          :style="carouselStyle"
          @transitionend="handleTransitionEnd"
        >
          <div
            class="products_content_item"
            v-for="(item, index) in displayProducts"
            :key="index"
          >
            <div class="discount" v-if="item.discount">{{ item.discount }}%</div>
            <div class="image">
              <a :href="'/product/'+item.id">
              <img class="product_image" :src="item.image" />
              </a>
              <div class="product_type" v-if="item.is_new == 1">{{ this.$page.props.language.new }}</div>
              <div class="product_type" v-else-if="item.is_bestseller == 1">{{ this.$page.props.language.bestsellers }}</div>
              <div class="like">
                <img src="images/like.svg"/>
              </div>
            </div>
            <div class="text_cotent">
              <div class="title">{{ item[`name_${$page.props.locale}`] }}</div>
              <div class="rating">
                <img src="/images/stars.png" />
                <div>(100)</div>
              </div>
              <div class="size">
                <span>{{ item.size }}</span>
                <span>{{ item.type }}</span>
              </div>
              <div class="price">
                <span class="big">{{ item.price }} դ</span>
                <span
                  v-if="item.discount"
                  class="small"
                >{{ item.price + (item.price * item.discount / 100) }} դ</span>
              </div>
            </div>
            <button>{{ this.$page.props.language.add_to_cart }}</button>
          </div>
        </div>
      </div>

      <div class="arrow right">
        <button @click="nextSlide">
          <img src="images/arrow_carusel.svg"/>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      products: [],
      displayProducts: [],
      currentIndex: 5,
      itemWidth: 222.5,
      isTransitioning: false,
      transitionEnabled: true,
      categories: [],
      categoryBanners: [
        {
            id:1,
            title: this.$page.props.language.bestsellers,
            type:true,
        },
        {
            id:2,
            title: this.$page.props.language.new,
            type:false,
        },
        {
            id:3,
            title: this.$page.props.language.discounts,
            type:false,
        },
      ],
    };
  },
  computed: {
    carouselStyle() {
      return {
        transform: `translateX(-${this.currentIndex * this.itemWidth}px)`,
        transition: this.transitionEnabled ? "transform 0.5s ease-in-out" : "none",
      };
    },
  },
  mounted() {
    axios.get("/random-categories").then((response) => {
      this.categories = response.data.categories
    });
    this.loadProducts();
    // this.loadCategories();
    // this.loadCategoryBanners();
  },
  methods: {
    async loadProducts() {
      try {
        const { data } = await axios.get("/products?is_bestseller=1");
        this.products = data.products.data;

        const head = this.products.slice(-5);
        const tail = this.products.slice(0, 5);
        this.displayProducts = [...head, ...this.products, ...tail];
        // console.log(this.displayProducts)
      } catch (err) {
        console.error("Ошибка при загрузке продуктов:", err);
      }
    },
    // async loadCategories() {
    //   try {
    //     const { data } = await axios.get("/categories");
    //     this.categories = data.categories || [];
    //   } catch (err) {
    //     console.error("Ошибка при загрузке категорий:", err);
    //   }
    // },
    // async loadCategoryBanners() {
    //   try {
    //     const { data } = await axios.get("/category-banners");
    //     this.categoryBanners = data || [];
    //   } catch (err) {
    //     console.error("Ошибка при загрузке баннеров категорий:", err);
    //   }
    // },
    changeProducts(id){
      this.categoryBanners
      this.categoryBanners.forEach((cat) => {
        cat.type = false;
        if(cat.id == id){
          cat.type = true;
        }
      });
      if(id == 1){
        axios.get("/products?is_bestseller=1").then((response) => {
          this.products = response.data.products.data.slice(0, 5);
          const head = this.products.slice(-5);
          const tail = this.products.slice(0, 5);
          this.displayProducts = [...head, ...this.products, ...tail];
        });
      }else if(id == 2){
        axios.get("/products?is_new=1").then((response) => {
          this.products = response.data.products.data.slice(0, 5);
          const head = this.products.slice(-5);
          const tail = this.products.slice(0, 5);
          this.displayProducts = [...head, ...this.products, ...tail];
        });
      }else{
        axios.get("/products?discount=1").then((response) => {
            this.products = response.data.products.data.slice(0, 5);
            const head = this.products.slice(-5);
            const tail = this.products.slice(0, 5);
            this.displayProducts = [...head, ...this.products, ...tail];
        });
      }
    },
    nextSlide() {
      if (this.isTransitioning) return;
      this.isTransitioning = true;
      this.transitionEnabled = true;
      this.currentIndex++;
    },

    prevSlide() {
      if (this.isTransitioning) return;
      this.isTransitioning = true;
      this.transitionEnabled = true;
      this.currentIndex--;
    },
    handleTransitionEnd() {
      const total = this.products.length;

      if (this.currentIndex >= total + 5) {
        this.transitionEnabled = false;
        this.currentIndex = 5;
      }

      if (this.currentIndex < 5) {
        this.transitionEnabled = false;
        this.currentIndex = total + 4;
      }

      // ✅ Сброс блокировки
      this.isTransitioning = false;
    }
  },
};
</script>

<style scoped lang="scss">
.categories_content{
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 1100px;
  margin: 0 auto;
  .category_item{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 250px;
    height: 50px;
    background:#D3E1D4 ;
    border-radius: 25px;
    font-size: .75rem;
    font-weight: 700;
    color: #000;
    text-transform: uppercase;
  }
}
.categories_content_small{
  justify-content: center!important;
  margin-bottom: 25px;
  .category_item{
    margin: 0 10px;
    width: 150px;
    height: 40px;
    margin-top: 1.5625rem;
    color: #000;
    background: #FFF;
    border: 1px solid #000;
    cursor: pointer;
  }
  .category_item_active{
    background:#D3E1D4 ;
  }
}
.carousel_full_wrapper {
  width: 1100px;
  margin: 1rem auto;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.carousel_wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  position: relative;

  .carousel_title {
    text-align: left;
    width: 100%;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #014e2e;
  }

  .arrow {
    // background: #ffffff;
    color: #014e2e;
    font-size: 24px;
    width: 50px;
    height: 100%;
    position: absolute;
    z-index: 1;

    button {
      display: flex;
      align-items: center;
      justify-content: center;
      background: #F4F3F7;
      cursor: pointer;
      position: absolute;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      top: calc(50% - 20px);
      border: none;
      img{
        width: 24px;
        height: 24px;
      }
    }
    &.left {
      left: 0;
      // background: linear-gradient(to right, white 80%, transparent 100%);
      button{
        right: 68px;
      }
    }

    &.right {
      right: 0;
      // background: linear-gradient(to left, white 80%, transparent 100%);
       button{
        img{
          transform: rotate(180deg);
        }
        left: 68px;
      }
    }
  }
}

.products_content_wrapper {
  overflow: hidden;
  width: 100%;
}

.products_content {
  display: flex;
}

.products_content_item {
  width: 200px;
  margin-right: 23px;
  flex-shrink: 0;
  height: 440px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.11);
  border-radius: 5px;
  position: relative;
  background: #fff;

  .image {
    position: relative;
    .product_image {
      width: 200px;
      height: 230px;
      // margin-bottom: 12px;
      object-fit: cover;
      display: block;
      border-radius: 8px 8px 0 0;
    }
    .product_type{
      position: absolute;
      left: 10px;
      top: 10px;
      color: #014E2E;
      font-size: 12px;
      font-weight: 500;
    }
    .like{
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      height: 32px;
      width: 32px;
      border-radius: 50%;
      right: 10px;
      bottom: 10px;
      background: #FFFFFF;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      img{
        height: 16px;
        width: 16px;
      }
    }
  }

  .text_cotent {
    padding: 0 10px;
    height: 155px;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;

    .title {
      font-size: 1rem;
    }

    .rating {
      display: flex;
      align-items: center;

      img {
        height: 14px;
      }

      div {
        margin-left: 10px;
      }
    }

    .size {
      font-size: 12px;
      color: #767676;
      span{
        margin-right: 5px;
      }
    }

    .price {
      .big {
        font-size: 18px;
        color: #014E2E;
        font-weight: 600;
      }

      .small {
        font-size: 12px;
        text-decoration: line-through;
        font-weight: 500;
        margin-left: 3px;
        color: #B3261E;
      }
    }
  }

  button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 42px;
    font-size: 16px;
    background: #BA1051;
    width: 180px;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    border-radius: 6px;
    margin: 2px auto 10px auto;
    border: none;
  }

  .discount {
    position: absolute;
    top: 0;
    right: 0;
    background: #143616;
    color: white;
    width: 55px;
    height: 33px;
    border-radius: 0 8px 0 8px;
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>
