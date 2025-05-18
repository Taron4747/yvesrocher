<template>
  <div class="category_info_content">
    <div class="category_info_content_top">
      <span>Главная</span><span>></span><span>{{ category.name_ru }}</span>
    </div>
    <div class="category_info_content_middle">
      <div class="content_middle_left">
        <div class="title">{{ category.name_ru }}</div>
        <div class="text">Buy 1, get 1 at 50% off on all face care products*</div>
        <div class="text">96 item(s) found</div>
      </div>
      <div class="content_middle_right">
        <img :src="category.image">
      </div>
    </div>
    <div class="category_info_content_bottom">
      *The lower priced item will be offered at 50% off in the cart. Can be combined with other categories with the same offer. Excludes limited editions, clearance and sets. Promotional offers on the same product are not cumulative.
    </div>
  </div>
</template>

<script>


export default {
  components: {
   
  },
  props: {
    category:Object
  },
  mounted(){
    console.log(this.category)
  },
  data() {
    return {
      form: {
      },
    }
  },
  
  methods: {
    
  },
}
</script>
<style scoped lang="scss">
.category_info_content{
  width: 1140px;
  margin: 0 auto;
  padding-top: 12px;
  .category_info_content_top{
    margin-bottom: 16px;
    span{
      margin-right: 5px;
      color: #767676;
    }
  }
  .category_info_content_middle{
    display: flex;
    justify-content: space-between;
    height: 142px;
    .content_middle_left{
      display: flex;
      flex-direction: column;
      justify-content: center;
      .title{
        font-size: 38px;
        color: #014E2E;
      }
      .text{
        margin-top: 12px;
      }
    }
    .content_middle_right{
      width: 369px;
      height: 142px;
      img{
        height: 100%;
        width: 100%;
      }
    }
  }
  .category_info_content_bottom{
    color: #767676;
    margin-top: 12px;
  }
}
</style>
