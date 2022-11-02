<template>
    <nav>
        <div class="container">
            <div class="logo">
                <h2>iClubs</h2>
            </div>
            <div class="menu">
                <router-link to="/">Home</router-link>
                <router-link to="/clubs">Clubes</router-link>
                <router-link to="/users">Assinantes</router-link>
                <button @click="logout">Sair</button>
            </div>
        </div>
    </nav>
</template>

<script>
import Cookies from 'js-cookie';

export default {
    name: 'Navbar',
    props: {
    },
    methods: {
        async logout() {
            try {
                let res = await this.axios.get('/auth/logout');
                Cookies.remove('_adminToken');

                this.$router.push("/login");
            } catch (error) { }

        },
    }
}
</script>

<style scoped lang="scss">
nav {
    padding: 20px 50px;
    background: var(--black);
    background: linear-gradient(90deg, var(--green-dark) 0%, var(--green) 100%);
    box-shadow: 0px 0px 0px 1px var(--black-50);

    .container {
        max-width: 1120px;
        width: 100%;
        margin: 0 auto;

        display: flex;
        justify-content: space-between;
        align-items: center;

        .logo {
            flex: 1;

            h2 {
                color: var(--white);
            }
        }

        .menu {
            flex: 2;

            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 30px;

            a,
            button {
                color: var(--white);
                font: 400 1.1rem "Poppins", sans-serif;

                transition: filter 0.3s;

                &.router-link-exact-active {
                    border-bottom: 2px solid var(--green-dark);
                }

                &:hover {
                    filter: brightness(0.8);
                }
            }

            button {
                background: none;
                border: none;
            }
        }
    }
}
</style>