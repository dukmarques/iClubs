<template>
    <header class="header">
        <h1>{{ title }}</h1>
        <button @click="buttonMethod">{{ titleButton }}</button>
    </header>
</template>

<script>
export default {
    name: 'Header',
    props: {
        title: String,
        titleButton: String,
    },
    methods: {
        buttonMethod() {
            this.$emit('buttonHeader');
        }
    }
}
</script>

<style scoped lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap');

header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 10px;
    margin: 30px 0px;

    border-bottom: 1px solid var(--green-dark);

    h1 {
        font-family: 'Roboto';
        font-weight: 400;
        text-align: center;
        color: var(--white);
    }

    button {
        border: none;
        background: var(--green);
        padding: 15px 20px;
        color: var(--white);
        border-radius: 6px;

        transition: filter 0.3s;

        &:hover {
            filter: brightness(0.8);
        }
    }
}
</style>