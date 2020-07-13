<template>
    <div class="mini-cart d-flex align-items-center">
        <b-link class="pr-3">
            <img src="~assets/pics/icons/cart.svg" alt="icon"/>
            <span>Корзина</span>
            <div class="mini-cart__counter">7</div>
        </b-link>
    </div>
</template>

<script>
    export default {
        name: 'MiniCart',
    };
</script>

<style scoped lang="scss">
    .mini-cart {
        margin-right: 24px;
        position: relative;

        &__counter {
            background: $red;
            position: absolute;
            height: $counter-size;
            width: $counter-size;
            border-radius: 50%;
            top: 5px;
            left: 10px;
            line-height: $counter-size;
            text-align: center;
            font-size: 10px;
            color: #fff;
        }

        & a {
            color: $purple;
        }

        & img {
            height: 17px;
            margin-right: 10px;
        }
    }
</style>
