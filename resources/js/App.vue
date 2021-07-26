<template>
    <div class="main">

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Raleway:wght@500;800&display=swap"
            rel="stylesheet"
        />

        <Header />

        <div class="container">

            <div v-show="loader" class="hungry-4">
            </div>

            <div v-show="!loader" >
                <router-view></router-view>
            </div>

        </div>
        <Footer />

    </div>
</template>

<script>
import Header from "./components/Header";
import Footer from "./components/Footer";
export default {
    name: "App",
    components: {
        Header,
        Footer
    },
    data() {
        return {
            loader: true,
        };
    },
    created:function(){

        setTimeout(()=>{
            this.loader=false;
            },1000);
        window.scrollTo(0,0);

    }
}
</script>

<style lang="scss">

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: "Raleway", sans-serif;
}

body {
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    min-height: 100vh;
}

.hungry-4 {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50% , -50%);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 8px solid #d1914b;
    --c:radial-gradient(farthest-side, #d64123 94%,#0000);
    --b:radial-gradient(farthest-side, #000 94%,#0000);
    background:
    var(--c) 11px 15px,
    var(--b) 6px 15px,
    var(--c) 35px 23px,
    var(--b) 29px 15px,
    var(--c) 11px 46px,
    var(--b) 11px 34px,
    var(--c) 36px 0px,
    var(--b) 50px 31px,
    var(--c) 47px 43px,
    var(--b) 31px 48px,
    #f6d353;
    background-size: 15px 15px,6px 6px;
    background-repeat: no-repeat;
    animation: hu4 1s infinite;
}
@keyframes hu4 {
  0%     {-webkit-mask:conic-gradient(#0000 0     ,#000 0)}
  16.67% {-webkit-mask:conic-gradient(#0000 60deg ,#000 0)}
  33.33% {-webkit-mask:conic-gradient(#0000 120deg,#000 0)}
  50%    {-webkit-mask:conic-gradient(#0000 180deg,#000 0)}
  66.67% {-webkit-mask:conic-gradient(#0000 240deg,#000 0)}
  83.33% {-webkit-mask:conic-gradient(#0000 300deg,#000 0)}
  100%   {-webkit-mask:conic-gradient(#0000 360deg,#000 0)}
}


ul {
    list-style-type: none;
    padding: 0;
    margin: 0;

    li {
        margin: 10px 0;
    }
}
</style>
