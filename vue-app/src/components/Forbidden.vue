<template>
  <div id="app">
    <div>403</div>
    <div class="txt">
      Forbidden<span class="blink">_</span>
    </div>
  </div>
</template>

<style scoped lang="scss">
@import "../assets/styles/fonts";
@import "../assets/styles/colors";

html, body {
    width: 100%;
    height: 100%;
    margin: 0;
}

* {
    font-family: 'Press Start 2P', cursive;
    box-sizing: border-box;
}

#app {
    padding: 1rem;
    display: flex;
    height: 100%;
    justify-content: center;
    align-items: center;
    color: $color;
    text-shadow: 0px 0px $glowSize;
    font-size: 6rem;
    flex-direction: column;

    .txt {
        font-size: 1.8rem;
    }
}

@keyframes blink {
    0% {
        opacity: 0
    }
    49% {
        opacity: 0
    }
    50% {
        opacity: 1
    }
    100% {
        opacity: 1
    }
}

.blink {
    animation-name: blink;
    animation-duration: 1s;
    animation-iteration-count: infinite;
}
</style>
