<template>
  <div class="with_you_content">
    <div class="title" v-html="this.$page.props.language.yves_with_you"></div>
    <div class="content">
      <div class="content_left">
        <img src="/images/push-1.svg"/>
      </div>
      <div class="content_right">
        <div class="top"> <img src="/images/push-2.png"/></div>
        <div class="bottom"> <img src="/images/push-3.svg"/></div>
      </div>
    </div>
  </div>
</template>

<script>


export default {
  components: {

  },
  props: {
  },
  mounted(){
    
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
.with_you_content{
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin: 50px auto 0 auto;
  width: 1140px;
  color: #014E2E;
  .title{
    font-size: 28px;
    margin-bottom: 30px;
  }
  .content{
    display: flex;
    justify-content: space-between;
    width: 100%;
    .content_right{
      .top{
        margin-bottom: 16px;
      }
    }
  }
 
}
</style>
