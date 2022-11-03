<template>
    <div class="container">
        <div class="content">
            <img src="@/assets/login-img.png" alt="Pessoas praticando esporte">
            <h1>Vem torcer com a gente!</h1>
        </div>
        <div class="form">
            <form @submit.prevent="login">
                <h1>i<span>C</span>lubs</h1>
                <p>Login</p>
                <input type="email" v-model="email" placeholder="example@iclubs.com">
                <input type="password" v-model="password" placeholder="*****">
                <button type="submit">Acessar</button>
            </form>
        </div>
    </div>
</template>

<script>
import Cookies from 'js-cookie';
import { createToast } from 'mosha-vue-toastify';
import 'mosha-vue-toastify/dist/style.css'

export default {
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        async login() {
            if (this.email !== '' && this.password !== '') {
                try {
                    const response = await this.axios.post('/auth', { email: this.email, password: this.password });
                    Cookies.set("_adminToken", response.data);
                    this.axios.defaults.headers.common["Authorization"] = "Bearer " + response.data;

                    this.$router.push('/');

                } catch (error) {
                    console.log(error)
                    this.toast(error.response.data.message);
                }
            } else {
                this.toast('Campos vázios', 'Preencha todos os campos', 'warning', '#ffc107');
            }
        }
    },
    setup() {
        const toast = (title, description = '', type = 'default', toastBackgroundColor) => {
            createToast({
                title,
                description
            },
                {
                    showIcon: false,
                    transition: 'zoom',
                    type,
                    hideProgressBar: false,
                    timeout: 3600,
                    toastBackgroundColor,
                })
        }
        return { toast }
    }
}
</script>

<style scoped lang="scss">
.container {
    width: 100%;
    height: 100vh;

    display: flex;
    justify-content: center;
    align-items: center;

    .content {
        flex: 1;
        background: var(--green-dark);
        height: 100%;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        img {
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: var(--white);
        }
    }

    .form {
        flex: 1;
        width: 100%;

        display: flex;
        justify-content: center;
        align-items: center;

        form {
            width: 90%;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 15px;

            h1 {
                font-size: 64px;
                color: var(--green);

                span {
                    font-size: 74px;
                    color: var(--green-dark);
                }
            }

            p {
                color: var(--green);
            }

            input {
                width: 100%;
                max-width: 350px;
                padding: 8px 15px;
                border-radius: 5px;
                border: 1px solid var(--gray);
                transition: focus 0.3s;

                &:focus {
                    outline: none !important;
                    border: 1px solid var(--green);
                }
            }

            button {
                border: none;
                color: var(--white);
                background: var(--green-dark);
                width: 100%;
                max-width: 350px;
                padding: 10px 15px;
                border-radius: 5px;

                transition: background 0.3s;

                &:hover {
                    background: var(--green);
                }
            }
        }
    }
}
</style>