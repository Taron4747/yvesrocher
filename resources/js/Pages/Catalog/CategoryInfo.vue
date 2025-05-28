<template>
  <div class="category_info_content">
    <div class="category_info_content_top">
      <a href="/">{{ this.$page.props.language.home }}</a><span v-if="categoryInfoData.category">></span>
      <a :href="categoryInfoData.categoryLink" v-if="categoryInfoData.subCategory || categoryInfoData.subsubcategory">{{categoryInfoData.category}}</a>
      <span v-else>{{categoryInfoData.category}}</span>
      <span v-if="categoryInfoData.subCategory">></span>
      <span v-if="categoryInfoData.subCategory">
        <a :href="categoryInfoData.subCategoryLink" v-if="categoryInfoData.subsubcategory">{{categoryInfoData.subCategory}}</a>
        <span v-else>{{categoryInfoData.subCategory}}</span>
      </span>
      <span v-if="categoryInfoData.subsubcategory">></span>
      <span v-if="categoryInfoData.subsubcategory">{{categoryInfoData.subsubcategory}}</span>
    </div>
    <div class="category_info_content_middle">
      <div class="content_middle_left">
        <div class="title" v-if="categoryInfoData.subsubcategory">{{categoryInfoData.subsubcategoryDesc}}</div>
        <div class="title" v-else-if="categoryInfoData.subCategory">{{categoryInfoData.subCategoryDesc}}</div>
        <div class="title" v-else>{{categoryInfoData.categoryDesc}}</div>
        <!-- <div class="text"  v-if="categoryInfoData.subsubcategoryDesc" v-html="categoryInfoData.subsubcategoryDesc"></div>
        <div class="text" v-else-if="categoryInfoData.subCategoryDesc" v-html="categoryInfoData.subCategoryDesc"></div>
        <div class="text" v-else v-html="categoryInfoData.categoryDesc"></div> -->
      </div>
      <div class="content_middle_right" v-if="categoryInfoData.category">
        <img :src="categoryInfoData.second_image">
      </div>
    </div>
  </div>
</template>

<script>


export default {
  components: {
   
  },
  props: {
    categoryInfoData:Object
  },
  mounted(){
    console.log(this.categoryInfoData);
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
    span,a{
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
        object-fit: cover;
      }
    }
  }
  .category_info_content_bottom{
    color: #767676;
    margin-top: 12px;
  }
}
</style>
