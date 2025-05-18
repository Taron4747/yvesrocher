<template>
  <div class="category_info_content">
    <div class="category_info_content_top">
      <span>Главная</span><span>></span><span>{{ category['name_ru'] }}</span>
    </div>
    <div class="category_info_content_middle">
      <div class="content_middle_left"></div>
      <div class="content_middle_right">

      </div>
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
}
</style>
